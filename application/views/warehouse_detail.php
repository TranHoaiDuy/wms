<style>
.content-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    padding: 20px;
}

.box {
    flex: 5;
    margin-right: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    background-color: #fff;
}

.box-body {
    padding: 15px;
}

.box-body img {
    height: 550px;
    width: 100%;
    object-fit: cover;
    display: block;
    margin: 0 auto;
    border-radius: 8px;
}

.box-tools {
    flex: 5;
    width: 100%;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 15px;
}

.data-column h3 {
    text-align: center;
    margin-bottom: 10px;
}

.data-column table {
    width: 100%;
    border-collapse: collapse;
}

.data-column table,
.data-column th,
.data-column td {
    border: 1px solid #ddd;
}

.data-column th,
.data-column td {
    padding: 8px;
    text-align: left;
}

.data-column th {
    background-color: #f2f2f2;
}
</style>

<div class="content-wrapper">

    <div class="box">
        <div class="box-body">
            <img src="<?php echo base_url('images/sodobai.png'); ?>" alt="Workplace" usemap="#workmap">
            <map name="workmap">
                <area coords="216,79,674,71,674,192,383,191,382,142,216,152" shape="poly" alt="Sơ đồ kho NVL"
                    title="Sơ đồ kho NVL" href="<?php echo base_url('wmsLayout/Shelves'); ?>">
            </map>
        </div>
    </div>

    <div class="box-tools">
        <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <button class="btn btn-primary" data-toggle="modal" data-target="#addLocationModal">
                        <i class="fa fa-plus"></i> Thêm Vị Trí
                    </button>
                </div>
            </div>
        </div>
        <?php if (!empty($locations)) { ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Khu vực</th>
                    <th>Vị trí</th>
                    <th>Loại NVL</th>
                    <th>Trạng thái</th>
                    <th style="text-align: center;">Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($locations as $location) { ?>
                <tr>

                    <td><?php echo htmlspecialchars($location->cargo_area); ?></td>
                    <td><?php echo htmlspecialchars($location->cargo_location); ?></td>
                    <td><?php echo htmlspecialchars($location->materialName); ?></td>
                    <td>
                        <?php
                                if ($location->cargo_status == 1) {
                                    echo "Sẵn Sàng";
                                } elseif ($location->cargo_status == 2) {
                                    echo "Không Sẵn Sàng";
                                } else {
                                    echo "Không Sử Dụng";
                                }
                                ?>
                    </td>
                    <td style="text-align: center;">
                        <form action="<?php echo base_url('wmsLayout/generate'); ?>" method="POST">
                            <a href="javascript:void(0);" class="btn btn-warning btn-sm btn-edit" data-toggle="modal"
                                data-target="#editLocationModal"
                                data-id="<?php echo htmlspecialchars($location->id); ?>"
                                data-area="<?php echo htmlspecialchars($location->cargo_area); ?>"
                                data-location="<?php echo htmlspecialchars($location->cargo_location); ?>"
                                data-materialid="<?php echo htmlspecialchars($location->materialId); ?>"
                                data-status="<?php echo htmlspecialchars($location->cargo_status); ?>">
                                <i class="fa fa-edit"></i> Sửa
                            </a>
                            <a href="<?php echo base_url('wmsLayout/delete/' . htmlspecialchars($location->id)); ?>"
                                class="btn btn-danger btn-sm"
                                onclick="return confirm('Bạn có chắc chắn muốn xóa không?');">
                                <i class="fa fa-trash"></i> Xóa
                            </a>
                            <input type="hidden" name="qrcode_location"
                                value="<?php echo htmlspecialchars($location->qrcode_location); ?>">
                            <button type="submit" class="btn btn-primary btn-sm btn-print-qrcode">
                                <i class="fa fa-print"></i> In QRCode
                            </button>
                        </form>


                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
        <?php } else { ?>
        <p>Không có dữ liệu để hiển thị.</p>
        <?php } ?>
        <div class="pagination-container">
            <div class="pagination">
                <?php echo $pagination; ?>
            </div>
        </div>
    </div>
</div>
</div>

</div>

</section>
</div>

<div class="modal fade" id="addLocationModal" tabindex="-1" role="dialog" aria-labelledby="addLocationModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="addMaterialModalLabel">Thêm Vị Trí</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo base_url('wmsLayout/store'); ?>" method="POST">
                    <div class="form-group">
                        <label for="cargo_area">Khu vực</label>
                        <input type="text" class="form-control" id="cargo_area" name="cargo_area"
                            placeholder="Nhập khu vực" required>
                    </div>
                    <div class="form-group">
                        <label for="cargo_location">Vị trí</label>
                        <input type="text" class="form-control" id="cargo_location" name="cargo_location"
                            placeholder="Nhập vị trí" required>
                    </div>
                    <div class="form-group">
                        <label for="materialId">Loại NVL</label>
                        <select class="form-control" id="materialId" name="materialId" required>
                            <option value="">Chọn nguyên vật liệu</option>
                            <?php foreach ($materials as $material): ?>
                            <option value="<?= $material->id ?>"><?= $material->materialName ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="cargo_status">Trạng thái</label>
                        <select class="form-control" id="cargo_status" name="cargo_status" required>
                            <option value="0">Không Sử Dụng</option>
                            <option value="1">Sẵn Sàng</option>
                            <option value="2">Không Sẵn Sàng</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-success btn-block"><i class="fa fa-plus"></i> Thêm </button>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="editLocationModal" tabindex="-1" role="dialog" aria-labelledby="editLocationModal"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title">Sửa Vị Trí</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo site_url('wmsLayout/update'); ?>" method="POST">
                    <input type="hidden" id="editLocationId" name="id">
                    <div class="form-group">
                        <label for="editCargo_area">Khu vực</label>
                        <input type="text" class="form-control" id="editCargo_area" name="cargo_area"
                            placeholder="Nhập khu vực" required>
                    </div>
                    <div class="form-group">
                        <label for="editCargo_location">Vị trí</label>
                        <input type="text" class="form-control" id="editCargo_location" name="cargo_location"
                            placeholder="Nhập vị trí" required>
                    </div>
                    <div class="form-group">
                        <label for="editMaterialId">Loại NVL</label>
                        <select class="form-control" id="editMaterialId" name="materialId" required>
                            <option value="">Chọn nguyên vật liệu</option>
                            <?php foreach ($materials as $material): ?>
                            <option value="<?= $material->id ?>"><?= $material->materialName ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="editCargo_status">Trạng thái</label>
                        <select class="form-control" id="editCargo_status" name="cargo_status" required>
                            <option value="1">Sẵn Sàng</option>
                            <option value="2">Không Sẵn Sàng</option>
                            <option value="0">Không Sử Dụng</option>
                        </select>
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
        $('#editLocationId').val($(this).data('id'));
        $('#editCargo_area').val($(this).data('area'));
        $('#editCargo_location').val($(this).data('location'));
        $('#editCargo_status').val($(this).data('status'));
        $('#editMaterialId').val($(this).data('materialid'));
    });
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.qrcode/1.0/jquery.qrcode.min.js"></script>