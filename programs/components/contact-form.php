                <form id="contactForm">
                    <div class="row">
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label><span class="text-danger">*</span> 姓名：</label>
                            <input type="text" maxlength="128" id="contactName" class="form-control contact-required" />
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label><span class="text-danger">*</span> E-Mail：</label>
                            <input type="email" maxlength="128" id="contactEmail" class="form-control contact-required" />
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label><span class="text-danger">*</span> 手机号：</label>
                            <input type="text" maxlength="64" id="contactPhone" class="form-control contact-required" />
                        </div>
                        <div class="col-lg-3 col-md-6 col-sm-12">
                            <label>微信号：</label>
                            <input type="text" maxlength="64" id="contactWechat" class="form-control" />
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label><span class="text-danger">*</span> 所在城市：</label>
                            <input type="text" maxlength="128" id="contactCity" class="form-control contact-required" />
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-12">
                            <label><span class="text-danger">*</span> 所在高校：</label>
                            <input type="text" maxlength="128" id="contactSchool" class="form-control contact-required" />
                        </div>
                    </div>
                    <br/>
                    <div class="row pb-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 提问：</label>
                            <textarea type="text" rows="5" maxlength="2500" id="contactContent" class="form-control"></textarea>
                        </div>
                    </div>
                    <div id="contactMessage"></div>
                    <div class="row">
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-warning" id="homeContactSubmit">提交信息</button>
                        </div>
                    </div>
                </form>