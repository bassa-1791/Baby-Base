  <h1>会員登録</h1>

  <form action="signup/confirm" method="POST" class="form">
    <div class="form-item">
      <div class="form-item-label">
        <label for="name">氏名: </label>
      </div>
      <div class="form-item-control">
        <input type="text" name="name" id="name" class="form-item-input" required>
      </div>
    </div>

    <div class="form-item">
      <div class="form-item-label">
        <label for="username">ユーザー名: </label>
      </div>
      <div class="form-item-control">
        <input type="text" name="username" id="username" class="form-item-input" required>
      </div>
    </div>

    <div class="form-item">
      <div class="form-item-label">
        <label for="email">メールアドレス: </label>
      </div>
      <div class="form-item-control">
        <input type="email" name="email" id="email" class="form-item-input" required>
      </div>
    </div>

    <div class="form-item">
      <div class="form-item-label">
        <label for="password">パスワード: </label>
      </div>
      <div class="form-item-control">
        <input type="password" name="password" id="password" class="form-item-input" required>
      </div>
    </div>

    <div class="form-item">
      <div class="form-item-label">
        <label for="password2">パスワード（再入力）: </label>
      </div>
      <div class="form-item-control">
        <input type="password" name="password2" id="password2" class="form-item-input" required>
      </div>
    </div>

    <div class="form-item">
      <input type="submit" value="入力内容の確認" class="button form-submit">
    </div>
    <div class="form-item">
      <a href="<?php echo PUBLIC_URL . 'auth/login'; ?>" class="button button-secondary">ログイン</a>
    </div>
  </form>