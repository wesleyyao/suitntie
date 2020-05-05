<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
        <a class="navbar-brand" href="#">适途教育</a>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item">
                <a class="nav-link" href="#">主页</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/suitntie/index.php" tabindex="-1" aria-disabled="true">人格测试</a>
            </li>
        </ul>
        <div id="userSection" class="form-inline my-2 my-lg-0">
        <?php if(isset($_SESSION["login_user"])): ?>
            <div id="userMenu">
                    <div class="dropdown">
                        <a class="dropdown-toggle user-menu" href="#/" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img id="loginUserAvatarInNav" src="#" style="display: inline-block; width: 40px; height: 40px;" alt="..."/>
                            <?php if(isset($_SESSION["new_test_result"])): ?><span class="notification-dot dot-medium text-danger"></span><?php endif; ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item" href="/suitntie/user.php">个人主页 
                            <?php if(isset($_SESSION["new_test_result"])): ?><span class="notification-dot dot-small text-danger"></span><?php endif; ?></a>
                            <a class="dropdown-item" href="/suitntie/public/auth/user-logout.php?redirect=<?php echo $_SERVER["REQUEST_URI"]; ?>">注销</a>
                        </div>
                    </div>
                </div>
        <?php else: ?>
            <a href="#/" class="my-2 my-sm-0" data-toggle="modal" data-target="#userLoginModal">登录</a>
        <?php endif; ?>
        </div>
    </div>
</nav>