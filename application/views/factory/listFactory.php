<style>
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-list"></i> Danh Sách Phân Xưởng
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-success" data-toggle="modal" data-target="#addFactoryModal"><i
                            class="fa fa-plus"></i> Thêm Phân Xưởng</a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="box box-purple">
                    <div class="box-header">
                        <div class="box-tools">
                            <form action="<?php echo base_url() ?>factory/listFactory" method="POST" id="searchList">
                                <div class="input-group">
                                    <input type="text" name="searchText" value=""
                                        class="form-control input-sm pull-right" style="width: 150px;"
                                        placeholder="Search" />
                                    <div class="input-group-btn">
                                        <button class="btn btn-sm btn-default searchList"><i
                                                class="fa fa-search"></i></button>
                                    </div>
                                </div>
                                <br>
                            </form>
                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Tên phân xưởng</th>
                                <th>Mô tả</th>
                                <th>Hành Động</th>
                            </tr>
                            <?php foreach ($factories as $fac) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fac->name); ?></td>
                                <td><?php echo htmlspecialchars($fac->description); ?></td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-warning btn-sm btn-edit"
                                        data-toggle="modal" data-target="#editFactoryModal"
                                        data-id="<?php echo htmlspecialchars($fac->id); ?>"
                                        data-name="<?php echo htmlspecialchars($fac->name); ?>"
                                        data-description="<?php echo htmlspecialchars($fac->description); ?>">
                                        <i class="fa fa-edit"></i> Sửa
                                    </a>
                                    <a href="<?php echo base_url('factory/delete/' . htmlspecialchars($fac->id)); ?>"
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
</div>
</div>
</section>
</div>
<!-- Modal Thêm Nguyên Vật Liệu -->
<div class="modal fade" id="addFactoryModal" tabindex="-1" role="dialog" aria-labelledby="addFactoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addFactoryModalLabel">Thêm Phân Xưởng</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url('factory/store'); ?>" method="POST">
                    <div class="form-group">
                        <label for="factoryName">Tên phân xưởng</label>
                        <input type="text" class="form-control" id="factoryName" name="name"
                            placeholder="Nhập tên phân xuởng" required>
                    </div>
                    <div class="form-group">
                        <label for="factoryDescription">Mô tả</label>
                        <textarea class="form-control" id="factoryDescription" name="description" rows="3"
                            placeholder="Nhập mô tả xưởng" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Thêm Phân
                        Xưởng</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Sửa Nguyên Vật Liệu -->
<div class="modal fade" id="editFactoryModal" tabindex="-1" role="dialog" aria-labelledby="editFactoryModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="editFactoryModalLabel">Sửa Phân Xưởng</h4>
            </div>
            <div class="modal-body">
                <form id="editFactoryForm" action="<?php echo site_url('factory/update'); ?>" method="POST">
                    <input type="hidden" id="editFactoryId" name="id">
                    <div class="form-group">
                        <label for="editFactoryName">Tên phân xưởng</label>
                        <input type="text" class="form-control" id="editFactoryName" name="name"
                            placeholder="Nhập tên phân xưởng" required>
                    </div>
                    <div class="form-group">
                        <label for="editFactoryDescription">Mô tả</label>
                        <textarea class="form-control" id="editFactoryDescription" name="description" rows="3"
                            placeholder="Nhập mô tả phân xưởng" required></textarea>
                    </div>

                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-save"></i> Cập
                        Nhật</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- <script src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/bootstrap.bundle.min.js'); ?>"></script> -->
<script>
$(document).ready(function() {
    $('.btn-edit').on('click', function() {
        $('#editFactoryId').val($(this).data('id'));
        $('#editFactoryName').val($(this).data('name'));
        $('#editFactoryDescription').val($(this).data('description'));
    });
});
</script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('ul.pagination li a').click(function(e) {
        e.preventDefault();
        var link = jQuery(this).get(0).href;
        var value = link.substring(link.lastIndexOf('/') + 1);
        jQuery("#searchList").attr("action", baseURL + "factory/listFactory/" + value);
        jQuery("#searchList").submit();
    });
});
</script>