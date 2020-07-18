<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-labelledby="editProfileModaltitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testResultTitle">修改资料</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="profileEditForm" style="padding: 0 20px">
                        <!-- <div class="col col-lg-6 col-md-12">
                            <label for="profileEditEmail"><span class="text-danger">*</span> 邮箱：</label>
                            <input type="text" class="form-control required-input" id="profileEditEmail"
                                maxlength="128" disabled/>
                        </div> -->
                    <br />
                    <div class="form-row">
                        <!-- <div class="col col-lg-8 col-md-12">
                            <label for="profileEditPhone"><span class="text-danger">*</span> 电话：</label>
                            <input type="text" class="form-control required-input" id="profileEditPhone"
                                maxlength="25" disabled/>
                        </div> -->
         
                        <div class="col col-lg-8 col-md-12">
                            <label for="profileEditNickname">昵称：</label>
                            <input type="text" class="form-control" id="profileEditNickname" maxlength="256" />
                        </div>
                        <div class="col col-lg-4 col-md-12">
                            <label for="profileEditSex">性别：</label>
                            <select class="form-control" id="profileEditSex">
                                <option value="1">男</option>
                                <option value="2">女</option>
                            </select>
                        </div>
                    </div>
                    <br />
                    <div class="form-row">
                        <div class="col col-lg-4 col-md-6">
                            <label for="profileEditCity">城市：</label>
                            <input type="text" class="form-control" id="profileEditCity" maxlength="256" />
                        </div>
                        <div class="col col-lg-4 col-md-6">
                            <label for="profileEditProvince">省份：</label>
                            <input type="text" class="form-control" id="profileEditProvince" maxlength="256" />
                        </div>
                        <div class="col col-lg-4 col-md-6">
                            <label for="profileEditCountry">国家：</label>
                            <input type="text" class="form-control" id="profileEditCountry" maxlength="256" />
                        </div>
                    </div>
                    <br />
                    <div id="profileEditMessage"></div>
                    <div class="form-row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn ghostSecBtn" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn primBtn">更新</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>