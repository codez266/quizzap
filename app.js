'use strict';
(function(){
	var baseUrl = '/project/spons/';
	// Declare app level module which depends on views, and components
	var myApplication = angular.module("njath", [
	  'ngRoute',
	  //'myApp.view1',
	  //'myApp.view2',
	  'myApp.version'
	]);/*.
	config(['$routeProvider', function($routeProvider) {
	  $routeProvider.otherwise({redirectTo: '/view1'});
	}]);*/
	//var ajaxService = angular.module( 'ajaxService', ['$http'] );
	//ajaxService.factory( 'get', )
    fetchData().then(bootstrapApplication);

    function fetchData() {
        var initInjector = angular.injector(["ng"]);
        var $http = initInjector.get("$http");

        return $http.get("/project/quizzap/init").then(function(response) {
            //myApplication.constant('user', response.data.user);
            myApplication.constant('server', {'url': response.data.server});
            //myApplication.service( 'user',['server','$http', User ] );
        }, function(errorResponse) {
            // Handle error case
        });
    }

    function bootstrapApplication() {
        angular.element(document).ready(function() {
            angular.bootstrap(document, ["njath"]);
        });
    }

    myApplication.controller( 'UserCtrl',['$scope','$http', function($scope,$http){
		var self = this;
		var url = 'userdata';

		this.init = function () {
			return $http.get( url ).then( function( response ) {
				$scope.user = response.data;
			},function(errorResponse){
			} );
		}

		this.refresh = function() {
			self.init();
		}

	    this.init();
	} ] );

	myApplication.controller( 'QuestionListCtrl',['$scope','$http', function($scope,$http){
		var self = this;
		var url = 'questions';

		this.init = function () {
			return $http.get( url ).then( function( response ) {
				self.questions = response.data;
			},function(errorResponse){
			} );
		}

		this.refresh = function() {
			self.init();
		}

	    this.init();
	} ] );

	myApplication.controller( 'QuestionCtrl',['$scope','$http','$routeParams', function($scope,$http,$routeParams){
		var self = this;
		var url = 'questions/' + $routeParams.id;

		this.init = function () {
			return $http.get( url ).then( function( response ) {
				$scope.question = response.data;
			},function(errorResponse){
			} );
		}

		this.refresh = function() {
			self.init();
		}

	    this.init();
	} ] );

	myApplication.config(function($routeProvider) {
	$routeProvider

	    // route for the home page
	    .when('/', {
	        templateUrl : 'pages/questionlist.html',
	        controller  : 'QuestionListCtrl'
	    })

	    // route for question page
	    .when('/questions/:id', {
	    	templateUrl : 'pages/question.html',
	    	controller  : 'QuestionCtrl'
	    })
	});
})();

