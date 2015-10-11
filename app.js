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
    fetchData().then(bootstrapApplication);

    /**
     * Initial request made by application during bootstrap to fetch basic data about user and server
     * required for bootstrapping
     * @return
     */
    function fetchData() {
        var initInjector = angular.injector(["ng"]);
        var $http = initInjector.get("$http");

        return $http.get("init").then(function(response) {
            myApplication.constant('server', {'url': response.data.server});
        }, function(errorResponse) {
            // Handle error case
        });
    }

    function bootstrapApplication() {
        angular.element(document).ready(function() {
            angular.bootstrap(document, ["njath"]);
        });
    }

    function Status() {
    	this.error = false;
    	this.state = "";
    	this.success = false;
    }

    /**
     * User service to store user details and update scores
     */
    function User( $http ){
        var self = this,
            url = "userdata";
        this.init = function () {
            return $http.get( url );
        }

        /**
         * Set details of user on this object
         * @param string name of user
         * @param int level of user
         * @param int tscore total score of user
         * @param int lscore level score of user
         */
        this.setDetails = function(name,level,tscore,lscore ){
            self.username = name;
            self.level = level;
            self.tscore = tscore;
            self.lscore = lscore;
        }
    }

    myApplication.service( 'User', ['$http', User] );

    /**
     * QuestionsList service to provide and manage list of questions for the user at a particular
     * level
     * @param {Object} $http Service used to query questions from rest api
     */
    function QuestionsList( $http ) {
    	var self = this,
    		url = "questions",
    		urlAnswer = "questions/submit/";
    	/**
    	 * questions object where key is questions id, and value is an object representing question
    	 * @type {Object}
    	 */
    	self.questions = {};

    	/**
    	 * Function to handle initialization of questions list. Returns a promise
    	 */
    	this.init = function() {
    		return $http.get( url ).then(function(response) {
    			for( var i in response.data ) {
    				var id = response.data[i];
    				self.questions[id.q_id] = {};
    			}
	        }, function(errorResponse) {
	            // Handle error case
	        });
    	}

        /**
         * Function to request question with id from server
         * @param string id of question to get
         * @return $http promise object
         */
        this.loadQuestion = function( id ){
            return $http.get( url + '/' + id );
        }

    	/**
    	 * Sets the questions array to blank object
    	 * @return
    	 */
    	this.destroy = function() {
    		self.questions = {};
    	}

    	/**
    	 * Submits answer to the question with id and returns a status object
    	 * @param  {int} id Id of the question to submit answer for
    	 * @return {[type]}
    	 */
    	this.answer = function( id, answer ) {
            //console.log(self.questions)
    		// question is is valid, submit an answer
    		if ( self.questions.hasOwnProperty( id ) === true ) {
    			var submitUrl = urlAnswer + 'q' + id;
    			return $http.post( submitUrl, {answer:answer} ).then( function( response ) {
    				// increment score on success, or update score?
    			}, function( errorResponse ) {
    				// Handle error
    			} );
    		} else {
    			// do something on error
    		}
    	}

    	/**
    	 * Reinitialize the list of questions, probably on level change or network reset
    	 * @return
    	 */
    	this.refresh = function() {
			return self.init();
		}

		this.init();
    }
    myApplication.service( 'questionsList', [ '$http', QuestionsList ] );

    myApplication.controller( 'UserCtrl',['User','$scope','$http', function($user,$scope,$http){
		var self = this;
		var url = 'userdata';

		this.init = function () {
			$user.init().then( function( response ) {
                $user.setDetails(response.data.username,response.data.level,response.data.t_score,response.data.l_score);
                $scope.username = $user.username;
                $scope.level = $user.level;
            },function(errorResponse){
			} );
		}

		this.refresh = function() {
			self.init();
		}

	    this.init();
	} ] );

	myApplication.controller( 'QuestionListCtrl',['questionsList', '$scope','$http', function(questionsList,$scope,$http){
		var self = this,
			url = 'questions';
		$scope.questions = [];

		/**
		 * Initialize the list of questions in this scope with their ids
		 * Waits for the questionsList service to deliver questions and then initializes them
		 * @return
		 */
		this.init = function() {
			questionsList.init()
			// execute when request completed
			.then( function(response) {
				for( var q in questionsList.questions ) {
					$scope.questions.push( q );
				}
	        }, function(errorResponse) {
	            // Handle error case
	        });
		}

		this.refresh = function() {
			self.init();
		}

	    this.init();
	} ] );

	myApplication.controller( 'QuestionCtrl',['questionsList', '$scope','$http','$routeParams',
			function($questionsList, $scope,$http,$routeParams){
		var self = this;
		var url = 'questions/' + $routeParams.id;

		this.init = function () {
			$questionsList.loadQuestion( $routeParams.id ).then( function( response ) {
				$scope.question = response.data;
                $scope.question.Q_ans = "Your answer here";
				console.log(response.data);
				$questionsList.questions[$routeParams.id.substring(1)] = response.data;
			},function(errorResponse){
			} );
		}

		this.refresh = function() {
			self.init();
		}

        this.submitAnswer = function() {
            $questionsList.answer( $scope.question.Q_id, $scope.question.Q_ans );
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

