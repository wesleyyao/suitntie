<div class="modal fade" id="programModal" tabindex="-1" role="dialog" aria-labelledby="programModalTitle"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="programModalTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="programForm" action="" method="post">
                    <div class="row mt-3 mb-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 名称</label>
                            <input type="text" maxlength="128" class="form-control" name="program_name" id="programName"
                                value="" required />
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-12">
                            <label>概述</label>
                            <textarea maxlength="2500" class="form-control" name="program_desc"
                                id="programDesc"></textarea>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 状态</label>
                            <select class="form-control" name="program_status" id="programStatus" required>
                                <option value="open">可用</option>
                                <option value="close">禁用</option>
                            </select>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 专业类别</label>
                            <select class="form-control" name="program_category_id" id="programCateogryId" required>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="program_id" id="programId" value="" />
                    <input type="hidden" name="is_new" id="isNew" value="" />
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-success">提交</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div id="programMessage"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>