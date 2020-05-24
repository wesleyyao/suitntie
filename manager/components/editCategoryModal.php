<div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-labelledby="editCategoryTitle"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editCategoryTitle">编辑专业类别</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="editCategoryForm" action="" enctype="multipart/form-data" method="post">
                    <div class="row mt-3 mb-3">
                        <div class="col-12">
                            <label><span class="text-danger">*</span> 名称</label>
                            <input type="text" maxlength="128" class="form-control" name="edit_category_name"
                                id="editCategoryName" value="" required />
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-12">
                            <label>图标</label>
                            <div class="custom-file mb-3">
                                <input type="file" class="form-control" name="editCategoryImg" />
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3 mb-3">
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <label><span class="text-danger">*</span> 排序</label>
                            <input type="number" class="form-control" max="999" name="edit_category_index"
                                id="editCategoryIndex" requried />
                        </div>
                        <div class="col-lg-3 col-md-4 col-sm-6">
                            <label><span class="text-danger">*</span> 状态</label>
                            <select class="form-control" name="edit_category_status" id="editCategoryStatus" required>
                                <option value="open">可用</option>
                                <option value="close">禁用</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" id="editCategoryId" name="edit_category_id" value="" />
                    <div class="row">
                        <div class="col-12 text-right">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">关闭</button>
                            <button type="submit" class="btn btn-success">提交</button>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div id="editCategoryMessage"></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>