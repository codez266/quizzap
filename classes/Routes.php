<?php
use \Slim\Slim;
class Routes {
	/**
	 * Route to get login page
	 * @return
	 */
	public static function getLogin() {
		$app = Slim::getInstance();
		$app->render( 'login.php' );
	}

	/**
	 * Routet to get a signup page
	 * @return
	 */
	public static function getSignup() {
		$app = Slim::getInstance();
		$app->render( 'signup.php' );
	}

	/**
	 * Route to post to a signup page for signing up
	 * @return
	 */
	public static function postSignup() {
		$app = Slim::getInstance();
		$request = $app->request;
		$post = $app->request->post();
		$user = new User(
				$post['username'],
				$post['password'],
				$post['roll'],
				$post['name'],
				$post['email'],
				$post['number'],
				1
			);
		$status = $user->verifyInput( $post );
		// if input passes, proceed
		if ( $status === true ) {
			$params = array(
				$post['name'],
				$post['username'],
				$post['roll'],
				$post['number'],
				$post['email'],
				$post['password']
			);
			$status = $user->addUser( $params );
			$_SESSION['err'] = $status;
			if ( $status === true ) {
				$_SESSION['user'] = $user->loadFromDb( $post['username'], $post['password'] );
				$app->redirect( $app->urlFor( "profileget" ) );
				//$app->render( 'profile.php' );
			} else {
				$app->render( 'signup.php' );
			}
		} else {
			$_SESSION['err'] = $status;
			$app->render( 'signup.php' );
		}
	}

	/**
	 * Route to post to a profile page, to reach through login
	 * @return
	 */
	public static function postProfile() {
		$app = Slim::getInstance();
		$request = $app->request;
		$username = $request->post( 'name' );
		$username = strip_tags( trim( $username ) );
		$pass = $request->post( 'password' );
		$pass = strip_tags( trim( $pass ) );
		$user = new User( $username, $pass );
		if ( ( $user = $user->loadFromDb( $username, $pass ) ) !== false ) {
			unset($user['password'],$user['u_id']);
			$_SESSION['user'] = $user;
			//$app->render( 'index.html' );
			$app->render( 'profile.php' );
		} else {
			$_SESSION['err'] = "Invalid username or password";
			$_SESSION['username'] = $username;
			$app->redirect( $app->urlFor( "login" ) );
		}
	}

	public static function getProfile() {
		$app = Slim::getInstance();
		if ( isset( $_SESSION['user'] ) ) {
			$app->render( 'profile.php' );
		}
	}

	/**
	 * Route to log out a user and return to login page
	 * @return
	 */
	public static function getLogout() {
		$app = Slim::getInstance();
		session_unset();
		$_SESSION['err'] = "Logged out";
		$app->redirect( $app->urlFor( "login" ) );
	}

	/**
	 * Initialize with user
	 * @return
	 */
	public static function getInit() {
		$app = Slim::getInstance();
		$response = $app->response;
		$req = $app->request;
		$fullPath = $req->getPath();
		$virtualPath = $req->getPathInfo();
		$basePath = substr( $fullPath,0, -strlen( $virtualPath ) );
		if ( isset( $_SESSION['user'] ) ) {
			$user = $_SESSION['user'];
			$res = array(
					'user' => $user,
					'server' => $basePath + '/'
				);
			JsonResponse::encode( $response, $res );
		}
	}


}
