<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">登录</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="loginForm">
                    <div class="row">
                        <div class="offset-2 col-8">
                            <label><span class="text-danger">*</span>邮箱:</label>
                            <input type="email" maxlength="128" id="officeEmail" class="form-control login-required" />
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-2 col-8">
                            <label><span class="text-danger">*</span>密码:</label>
                            <input type="password" maxlength="24" id="officePwd" class="form-control login-required" />
                        </div>
                    </div>
                    <br />
                    <div class="row">
                        <div class="offset-2 col-8">
                            <div id="loginMessage"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="offset-2 col-8 text-right">
                            <button type="submit" class="btn btn-warning">确认</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>