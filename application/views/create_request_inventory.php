<style>
.text-danger {
    color: red;
    font-size: 12px;
}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-list"></i> Tạo Phiếu Kiểm Kê
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-tools">
                        <form action="<?php echo base_url('create-request-inventory'); ?>" method="post"
                            onsubmit="return validateDates()">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 text-right">
                                        <div class="form-group">
                                            <button class="btn btn-primary"><i class="fa fa-save"></i> Tạo phiếu
                                                kiểm</button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="ma-chung-tu">Ngày Tạo Phiếu</label>
                                        <input type="date" class="form-control"
                                            value="<?php echo date('Y-m-d', strtotime($createdDtm ?? '')); ?>">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ma-chung-tu">Kho Kiểm Kê</label>
                                        <input type="text" class="form-control" name="wms" id="wms"
                                            value="Kho Nguyên Vật Liệu" readonly>

                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="nha-cung-cap">Người Kiểm Kê</label>
                                        <select class="form-control" name="stocktakingById" id="stocktakingById"
                                            required>
                                            <option value="">Chọn người kiểm kê</option>
                                            <?php foreach ($usersbyrole as $user): ?>
                                            <option value="<?php echo $user->userId; ?>">
                                                <?php echo htmlspecialchars($user->name); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ngay-du-kien">Ngày Kiểm Kê</label>
                                        <input type="date" class="form-control" name="stocktakingdtm"
                                            id="stocktakingdtm" required>
                                        <span id="stocktakingdtm-error" class="text-danger"></span>
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

    var exportDtm = document.getElementById("stocktakingdtm").value;
    var today = new Date().toISOString().split('T')[0];
    document.getElementById("-error").innerText = "";

    let isValid = true;
    if (exportDtm < today) {
        document.getElementById("stocktakingdtm-error").innerText =
            "Ngày kiểm kê không được trước ngày hiện tại.";
        isValid = false;
    }

    return isValid;
}
</script>