<?php

namespace Http\Controllers;

use Auth\User\LoginUser;
use Database\DBAccess;
use Http\Controllers\Controller;

class UserController extends Controller
{

  /**
   * GET users/[:id]
   *
   * @param [int] $id
   * @return void
   */
  public function fetchByID($id)
  {
    // もし自分のページなら遷移する
    if ($this->auth->check()) {
      $user = $this->auth->getUser();
      if ($id == $user->getId()) {
        $this->push("mypage");
      }
    }

    $params = $this->getUserPageInfo($id);

    // ユーザー情報の取得に失敗していれば
    if (!$params['user']) {
      $this->push("");
    }

    $this->view("user/user.php", $params);
  }

  /**
   * GET mypage
   *
   * @return void
   */
  public function mypage()
  {
    // ログイン確認
    if (!$this->auth->check()) {
      $this->push("auth/login");
    }

    $user = $this->auth->getUser();
    $user_id = $user->getId();
    $params = $this->getUserPageInfo($user_id);

    $this->view("user/index.php", $params);
  }

  /**
   * GET mypage/info
   *
   * @return void
   */
  public function info()
  {
    // ログイン確認
    if (!$this->auth->check()) {
      $this->push("auth/login");
    }

    $user = $this->auth->getUser();
    $user_id = $user->getId();

    $dba = DBAccess::getInstance();

    // usersテーブルからパスワード以外の情報を取得する
    $stmt = $dba->query("SELECT username, name, email FROM users WHERE id = ?", [$user_id]);
    $user = $stmt->fetch();

    $params = ['user' => $user];
    $this->view("user/info.php", $params);
  }

  /**
   * TODO: 作成
   * POST mypage/info/confirm
   */

  /**
   * POST mypage/info
   *
   * @return void
   */
  public function infoUpdate()
  {
    // ログイン確認
    if (!$this->auth->check()) {
      $this->push("auth/login");
    }

    $user = $this->auth->getUser();
    $user_id = $user->getId();

    // 入力検証

    $dba = DBAccess::getInstance();
    // 重複確認

    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $dba->query("UPDATE users SET username = ?, password = ?, name = ?, email = ? WHERE id = ? LIMIT 1;", [$_POST['username'], $hashed_password, $_POST['name'], $_POST['email'], $user_id]);

    $updateUser = new LoginUser($user_id, $_POST['username']);
    $this->auth->login($updateUser);

    $this->push("mypage");
  }

  /**
   * GET mypage/profit
   *
   * @return void
   */
  public function profit()
  {
    // ログイン確認
    if (!$this->auth->check()) {
      $this->push("auth/login");
    }

    $user = $this->auth->getUser();
    $user_id = $user->getId();

    $dba = DBAccess::getInstance();

    // transfersテーブルから商品名と利益を取得
    $stmt = $dba->query("SELECT transfers.amount, products.name FROM transfers LEFT OUTER JOIN transactions ON transfers.transaction_id = transactions.id LEFT OUTER JOIN products ON transactions.product_id = products.id WHERE transactions.user_id = ?;", [$user_id]);
    $transfers = $stmt->fetchAll();

    $params = ['transfers' => $transfers];
    $this->view("user/profit.php", $params);
  }

  /**
   * GET mypage/favorite
   *
   * @return void
   */
  public function favorite()
  {
    // ログイン確認
    if (!$this->auth->check()) {
      $this->push("auth/login");
    }
    // DB取得
    $this->view("user/favorite.php");
  }

  /**
   * GET mypage/block
   *
   * @return void
   */
  public function block()
  {
    // ログイン確認
    if (!$this->auth->check()) {
      $this->push("auth/login");
    }
    // DB取得
    $this->view("user/block.php");
  }


  /**
   * マイページや会員ページの情報を取得する
   *
   * @param integer $id
   * @return array
   */
  private function getUserPageInfo(int $id)
  {
    $dba = DBAccess::getInstance();

    // ユーザー情報の取得
    $stmt = $dba->query("SELECT id, username FROM users WHERE id = ?;", [$id]);
    $user = $stmt->fetch();

    // テーブルから出品した商品を取得
    $stmt = $dba->query("SELECT DISTINCT products.id, name, price, status, path FROM products LEFT OUTER JOIN pictures ON products.id = pictures.product_id WHERE products.user_id = ? LIMIT 30;", [$id]);
    $products = $stmt->fetchAll();

    return ['user' => $user, 'products' => $products];
  }
}
