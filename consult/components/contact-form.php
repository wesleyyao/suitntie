<div class="modal fade" id="contactConsultantModal" tabindex="-1" aria-labelledby="contactConsultantTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="contactConsultantTitle">联系导师</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row pb-2">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <label><span class="text-danger">*</span> 姓名：</label>
                        <input type="text" maxlength="128" id="ccName" class="form-control cc-required"
                            placeholder="姓名" />
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <label><span class="text-danger">*</span> E-Mail：</label>
                        <input type="email" maxlength="128" id="ccEmail" class="form-control cc-required"
                            placeholder="E-Mail" />
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <label><span class="text-danger">*</span> 手机号：</label>
                        <input type="text" maxlength="64" id="ccPhone" class="form-control cc-required"
                            placeholder="手机号" />
                    </div>
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <label>微信号：</label>
                        <input type="text" maxlength="64" id="ccWechat" class="form-control" placeholder="微信号" />
                    </div>
                </div>
                <div class="row pb-2">
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label><span class="text-danger">*</span> 所在城市：</label>
                        <input type="text" maxlength="128" id="ccCity" class="form-control cc-required"
                            placeholder="所在城市" />
                    </div>
                    <div class="col-lg-6 col-md-6 col-sm-12">
                        <label><span class="text-danger">*</span> 所在高校：</label>
                        <input type="text" maxlength="128" id="ccSchool" class="form-control cc-required"
                            placeholder="所在高校" />
                    </div>
                </div>
                <div class="row pb-3">
                    <div class="col-12">
                        <label><span class="text-danger">*</span> 提问：</label>
                        <textarea type="text" rows="5" maxlength="2500" id="ccContent" class="form-control"></textarea>
                    </div>
                </div>
                <div id="ccMessage"></div>
                <div class="row">
                    <div class="col-12 text-center">
                        <button class="btn primBtn" id="contactConsultant">提交信息</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>