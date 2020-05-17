<div class="modal fade" id="newCategoryModal" tabindex="-1" role="dialog" aria-labelledby="newCategoryTitle"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="newCategoryTitle">新增专业类别</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="newCategoryForm" action="" enctype="multipart/form-data" method="post">
                    <div class="row mt-3 mb-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 名称</label>
                            <input type="text" maxlength="128" class="form-control" name="new_category_name"
                                id="newCategoryName" value="" required />
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-12">
                            <label>图标</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="form-control" name="newCategoryImg" id="newCategoryFile" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 状态</label>
                            <select class="form-control" name="new_category_status" id="newCategoryStatus" required>
                                <option value="open">可用</option>
                                <option value="close">禁用</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-success">提交</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div id="newCategoryMessage"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>