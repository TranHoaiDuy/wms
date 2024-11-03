<style>
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-building"></i> Danh Sách Nhà Cung Cấp
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-success" data-toggle="modal" data-target="#addSupplierModal"><i
                            class="fa fa-plus"></i> Thêm Nhà Cung Cấp</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-purple">
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Tên Nhà Cung Cấp</th>
                                <th>Địa Chỉ</th>
                                <th>Điện Thoại</th>
                                <th>Email</th>
                                <th>Hành Động</th>
                            </tr>
                            <?php foreach ($suppliers as $supplier) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($supplier->supplierName); ?></td>
                                <td><?php echo htmlspecialchars($supplier->address); ?></td>
                                <td><?php echo htmlspecialchars($supplier->phone); ?></td>
                                <td><?php echo htmlspecialchars($supplier->email); ?></td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-warning btn-sm btn-edit"
                                        data-toggle="modal" data-target="#editSupplierModal"
                                        data-id="<?php echo htmlspecialchars($supplier->id); ?>"
                                        data-name="<?php echo htmlspecialchars($supplier->supplierName); ?>"
                                        data-address="<?php echo htmlspecialchars($supplier->address); ?>"
                                        data-phone="<?php echo htmlspecialchars($supplier->phone); ?>"
                                        data-email="<?php echo htmlspecialchars($supplier->email); ?>">
                                        <i class="fa fa-edit"></i> Sửa
                                    </a>
                                    <a href="<?php echo base_url('supplier/delete/' . htmlspecialchars($supplier->id)); ?>"
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
</div>
</section>
</div>

<!-- Modal Thêm Nhà Cung Cấp -->
<div class="modal fade" id="addSupplierModal" tabindex="-1" role="dialog" aria-labelledby="addSupplierModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addSupplierModalLabel">Thêm Nhà Cung Cấp</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url('supplier/store'); ?>" method="POST">
                    <div class="form-group">
                        <label for="supplierName">Tên Nhà Cung Cấp</label>
                        <input type="text" class="form-control" id="supplierName" name="supplierName"
                            placeholder="Nhập tên nhà cung cấp" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Địa Chỉ</label>
                        <input type="text" class="form-control" id="address" name="address" placeholder="Nhập địa chỉ"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Điện Thoại</label>
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Nhập số điện thoại"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="Nhập email"
                            required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Thêm Nhà Cung
                        Cấp</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sửa Nhà Cung Cấp -->
<div class="modal fade" id="editSupplierModal" tabindex="-1" role="dialog" aria-labelledby="editSupplierModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="editSupplierModalLabel">Sửa Nhà Cung Cấp</h4>
            </div>
            <div class="modal-body">
                <form id="editSupplierForm" action="<?php echo site_url('supplier/update'); ?>" method="POST">
                    <input type="hidden" id="editSupplierId" name="id">
                    <div class="form-group">
                        <label for="editSupplierName">Tên Nhà Cung Cấp</label>
                        <input type="text" class="form-control" id="editSupplierName" name="supplierName"
                            placeholder="Nhập tên nhà cung cấp" required>
                    </div>
                    <div class="form-group">
                        <label for="editAddress">Địa Chỉ</label>
                        <input type="text" class="form-control" id="editAddress" name="address"
                            placeholder="Nhập địa chỉ" required>
                    </div>
                    <div class="form-group">
                        <label for="editPhone">Điện Thoại</label>
                        <input type="text" class="form-control" id="editPhone" name="phone"
                            placeholder="Nhập số điện thoại" required>
                    </div>
                    <div class="form-group">
                        <label for="editEmail">Email</label>
                        <input type="email" class="form-control" id="editEmail" name="email" placeholder="Nhập email"
                            required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-save"></i> Cập Nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script>
<script>
$(document).ready(function() {
    $('.btn-edit').on('click', function() {
        $('#editSupplierId').val($(this).data('id'));
        $('#editSupplierName').val($(this).data('name'));
        $('#editAddress').val($(this).data('address'));
        $('#editPhone').val($(this).data('phone'));
        $('#editEmail').val($(this).data('email'));
    });
});
</script>