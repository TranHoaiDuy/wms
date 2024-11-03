<style>
.text-danger {
    color: red;
    font-size: 12px;
}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-list"></i> Tạo phiếu nhận
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-purple">
                    <div class="box-tools">
                        <form action="<?php echo base_url('create-goods-issue'); ?>" method="post"
                            onsubmit="return validateDates()">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 text-right">
                                        <div class="form-group">
                                            <button class="btn btn-success"><i class="fa fa-save"></i> Tạo phiếu
                                                nhận</button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="nha-cung-cap">Phân Xưởng Yêu Cầu</label>
                                        <select class="form-control" name="factory_id" id="phan-xuong" required>
                                            <option value="">Chọn phân xưởng yêu cầu</option>
                                            <?php foreach ($factories as $fa): ?>
                                            <option value="<?php echo $fa->id; ?>">
                                                <?php echo htmlspecialchars($fa->name); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <!-- <div class="col-md-6">
                                        <label for="ngay-du-kien">Ngày Tạo Phiếu</label>
                                        <input type="text" class="form-control" name="createdDtm" id="createdDtm" readonly>
                                    </div> -->
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="ma-chung-tu">Người Yêu Cầu</label>
                                        <input type="text" class="form-control" name="createBy" id="createBy"
                                            value="<?php echo $name ?>" readonly>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ngay-du-kien">Ngày Yêu Cầu</label>
                                        <input type="text" class="form-control" name="exportDtm" readonly
                                            value="<?php echo htmlspecialchars($currentDate ?? ''); ?>">
                                        <span id=" exportDtm-error" class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="ghi-chu">Ghi Chú</label>
                                    <textarea class="form-control" name="note" id="ghi-chu" rows="5"
                                        placeholder="Nhập ghi chú"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </section>
</div>
<script>
function validateDates() {

    var exportDtm = document.getElementById("exportDtm").value;
    var today = new Date().toISOString().split('T')[0];
    document.getElementById("exportDtm-error").innerText = "";

    let isValid = true;
    if (exportDtm <= today) {
        document.getElementById("exportDtm-error").innerText =
            "Ngày yêu cầu xuất phải sau ngày hiện tại.";
        isValid = false;
    }

    return isValid;
}
</script>