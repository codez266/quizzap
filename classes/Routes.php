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
				unset($_SESSION['pass']);
				$_SESSION['newLevel'] = true;
				self::initQuestions();
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
			unset($user['pass']);
			$_SESSION['user'] = $user;
			$app->render( 'index.html' );
			//$app->render( 'profile.php' );
		} else {
			$_SESSION['err'] = "Invalid username or password";
			$_SESSION['username'] = $username;
			$app->redirect( $app->urlFor( "login" ) );
		}
	}

	public static function getProfile() {
		$app = Slim::getInstance();
		if ( isset( $_SESSION['user'] ) ) {
			$app->render( 'index.html' );
			//$app->render( 'profile.php' );
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

	/**
	 * Return profile data as a json array
	 * @return
	 */
	public static function getProfileData() {
		$app = Slim::getInstance();
		$response = $app->response;
		$req = $app->request;
		$user = $_SESSION['user'];

		$res = array(
				'username' => $user['username'],
				'roll' => $user['roll'],
				't_score' => $user['t_score'],
				'l_score' => $user['l_score'],
				'level' => $user['level']
			);
		JsonResponse::encode( $response, $res );
	}

	/**
	 * Return list of questions
	 * @return
	 */
	public static function getQuestions() {
		$app = Slim::getInstance();
		$response = $app->response;
		$req = $app->request;
		$user = $_SESSION['user'];
		$query = "SELECT t1.q_id from info t1 LEFT JOIN questions t2 ON t1.q_id=t2.q_id WHERE t2.level=? AND t1.u_id=?";
		$db = DB::getInstance( 'Config' );
		$db->query( $query, array( $user['level'],$user['u_id'] ) );
		if ( $db->getError() !== true ) {
			$result = $db->getResult();
			JsonResponse::encode( $response, $result );
		}
	}

	/**
	 * Return list of questions
	 * @var int $id Id of the question to get
	 * @return
	 */
	public static function getQuestion( $qid ) {
		$qid = substr( $qid, 1 );
		$app = Slim::getInstance();
		$response = $app->response;
		$req = $app->request;
		$user = $_SESSION['user'];
		//$query = "SELECT t1.q_id,t2.name,t2.text,t2.img_name,t2.level from info t1 LEFT JOIN question t2 ON t1.q_id=t2.q_id WHERE q_id=? AND level=? AND u_id=?";
		$query = "SELECT q_id,name,text,img_name,level from questions WHERE q_id IN (SELECT q_id from info WHERE q_id=? AND u_id=?)";
		$db = DB::getInstance( 'Config' );
		$db->query( $query, array( $qid, $user['u_id'] ) );
		if ( $db->getError() !== true && $db->getCount() > 0 ) {
			$result = $db->getResult()[0];
			JsonResponse::encode( $response, $result );
		} else {
			JsonResponse::encode( $response, array( 'error' => 'Question is not accessible' ) );
		}
	}

	/**
	 * Add level questions to database
	 * @return
	 */
	public static function initQuestions() {
		// goto a new level? so add list of questions for that user
		if ( $_SESSION['newLevel'] === true ) {
			$user = $_SESSION['user'];
			$query = "SELECT * FROM questions WHERE level=?";
			$db = DB::getInstance( 'Config' );
			$db->query( $query, array( $user['level'] ) );
			$result = $db->getResult();
			$rand = array_rand( $result, LEVEL_QUESTIONS );
			$query = "";
			$data = array();
			foreach( $rand as $q ) {
				$uid = $user['u_id'];
				$query .= "INSERT INTO info ( u_id, q_id ) VALUES (?,?);";
				$data[] = $uid;
				$data[] = $result[$q]['q_id'];
			}
			$db->query( $query, $data );
			$_SESSION['newLevel'] = false;
		}
	}

}
