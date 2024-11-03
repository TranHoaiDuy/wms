<style>
.text-danger {
    color: red;
    font-size: 12px;
}
</style>
<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-list"></i> Tạo phiếu nhập kho
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-purple">
                    <div class="box-tools">
                        <form action="<?php echo base_url('create-goods-receipt'); ?>" method="post"
                            onsubmit="return validateDates()">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-xs-12 text-right">
                                        <div class="form-group">
                                            <button class="btn btn-success"><i class="fa fa-save"></i> Tạo
                                                phiếu</button>
                                        </div>
                                    </div>
                                </div>
                                <br>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="ma-chung-tu">Mã Chứng Từ</label>
                                        <input type="text" class="form-control" name="invoice_code" id="ma-chung-tu"
                                            placeholder="Nhập mã chứng từ" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="ngay-chung-tu">Ngày Chứng Từ</label>
                                        <input type="date" class="form-control" name="invoice_date" id="ngay-chung-tu"
                                            required>
                                        <span id="invoice-date-error" class="text-danger"></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-6">
                                        <label for="nha-cung-cap">Nhà Cung Cấp</label>
                                        <select class="form-control" name="supplier_id" id="nha-cung-cap" required>
                                            <option value="">Chọn nhà cung cấp</option>
                                            <?php foreach ($suppliers as $supplier): ?>
                                            <option value="<?php echo $supplier->id; ?>">
                                                <?php echo htmlspecialchars($supplier->supplierName); ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="ngay-du-kien">Ngày Dự Kiến Giao Hàng</label>
                                        <input type="date" class="form-control" name="delivery_date" id="ngay-du-kien"
                                            required>
                                        <span id="delivery-date-error" class="text-danger"></span>
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
    var invoiceDate = document.getElementById("ngay-chung-tu").value;
    var deliveryDate = document.getElementById("ngay-du-kien").value;
    var today = new Date().toISOString().split('T')[0];
    document.getElementById("invoice-date-error").innerText = "";
    document.getElementById("delivery-date-error").innerText = "";

    // Kiểm tra ngày chứng từ và ngày giao hàng
    let isValid = true;
    if (invoiceDate > today) {
        document.getElementById("invoice-date-error").innerText = "Ngày chứng từ phải trước ngày hiện tại.";
        isValid = false;
    }
    if (deliveryDate < today) {
        document.getElementById("delivery-date-error").innerText = "Ngày dự kiến giao hàng phải sau ngày hiện tại.";
        isValid = false;
    }

    return isValid; // Chỉ cho phép form submit nếu không có lỗi
}
</script>