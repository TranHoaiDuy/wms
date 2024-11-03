<style>
/*.table thead th {
    text-align: center;
}*/
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-list"></i> Danh Sách Nguyên Vật Liệu
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-success" data-toggle="modal" data-target="#addMaterialModal"><i
                            class="fa fa-plus"></i> Thêm Nguyên Vật Liệu</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-purple">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Mã Nguyên Vật Liệu</th>
                                <th>Tên Nguyên Vật Liệu</th>
                                <th>Số Lượng Quy Cách</th>
                                <th>Đơn Vị Tính</th>
                                <th>Hành Động</th>
                            </tr>
                            <?php foreach ($materials as $material) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($material->materialCode); ?></td>
                                <td><?php echo htmlspecialchars($material->materialName); ?></td>
                                <td><?php echo htmlspecialchars($material->quantity); ?></td>
                                <td><?php echo htmlspecialchars($material->unit); ?></td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-warning btn-sm btn-edit"
                                        data-toggle="modal" data-target="#editMaterialModal"
                                        data-id="<?php echo htmlspecialchars($material->id); ?>"
                                        data-code="<?php echo htmlspecialchars($material->materialCode); ?>"
                                        data-name="<?php echo htmlspecialchars($material->materialName); ?>"
                                        data-unit="<?php echo htmlspecialchars($material->unit); ?>"
                                        data-quantity="<?php echo htmlspecialchars($material->quantity); ?>">
                                        <i class="fa fa-edit"></i> Sửa
                                    </a>
                                    <a href="<?php echo base_url('material/delete/' . htmlspecialchars($material->id)); ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">
                                        <i class="fa fa-trash"></i> Xóa
                                    </a>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <div class="box-footer clearfix">
                        <?php echo $this->pagination->create_links(); ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<!-- Modal Thêm Nguyên Vật Liệu -->
<div class="modal fade" id="addMaterialModal" tabindex="-1" role="dialog" aria-labelledby="addMaterialModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addMaterialModalLabel">Thêm Nguyên Vật Liệu</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url('material/store'); ?>" method="POST">
                    <div class="form-group">
                        <label for="materialCode">Mã Nguyên Vật Liệu</label>
                        <input type="text" class="form-control" id="materialCode" name="materialCode"
                            placeholder="Nhập mã nguyên vật liệu" required>
                    </div>
                    <div class="form-group">
                        <label for="materialName">Tên Nguyên Vật Liệu</label>
                        <input type="text" class="form-control" id="materialName" name="materialName"
                            placeholder="Nhập tên nguyên vật liệu" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Số Lượng Quy Cách</label>
                        <input type="text" class="form-control" id="quantity" name="quantity"
                            placeholder="Nhập số lượng quy cách" required>
                    </div>
                    <div class="form-group">
                        <label for="unit">Đơn Vị Tính</label>
                        <select class="form-control" id="unit" name="unit" required>
                            <option value="thùng">Thùng</option>
                            <option value="bao">Bao</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Thêm Nguyên
                        Vật Liệu</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sửa Nguyên Vật Liệu -->
<div class="modal fade" id="editMaterialModal" tabindex="-1" role="dialog" aria-labelledby="editMaterialModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="editMaterialModalLabel">Sửa Nguyên Vật Liệu</h4>
            </div>
            <div class="modal-body">
                <form id="editMaterialForm" action="<?php echo site_url('material/update'); ?>" method="POST">
                    <input type="hidden" id="editMaterialId" name="id">
                    <div class="form-group">
                        <label for="editMaterialCode">Mã Nguyên Vật Liệu</label>
                        <input type="text" class="form-control" id="editMaterialCode" name="materialCode"
                            placeholder="Nhập mã nguyên vật liệu" required>
                    </div>
                    <div class="form-group">
                        <label for="editMaterialName">Tên Nguyên Vật Liệu</label>
                        <input type="text" class="form-control" id="editMaterialName" name="materialName"
                            placeholder="Nhập tên nguyên vật liệu" required>
                    </div>
                    <div class="form-group">
                        <label for="editQuantity">Số Lượng Quy Cách</label>
                        <input type="text" class="form-control" id="editQuantity" name="quantity"
                            placeholder="Nhập số lượng quy cách" required>
                    </div>
                    <div class="form-group">
                        <label for="editUnit">Đơn Vị Tính</label>
                        <select class="form-control" id="editUnit" name="unit" required>
                            <option value="thùng">Thùng</option>
                            <option value="bao">Bao</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-save"></i> Cập
                        Nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!--
<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>-->
<script>
$(document).ready(function() {
    $('.btn-edit').on('click', function() {
        $('#editMaterialId').val($(this).data('id'));
        $('#editMaterialCode').val($(this).data('code'));
        $('#editMaterialName').val($(this).data('name'));
        $('#editQuantity').val($(this).data('quantity'));
        $('#editUnit').val($(this).data('unit'));
    });
});
</script>