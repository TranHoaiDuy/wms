<?php

$id = '';
$receipt_code = '';
$invoice_code =  '';
$invoice_date = '';
$supplier_id = '';
$delivery_date = '';
$note = '';
$status = '';
$receipt_datetime = '';
$mom_code  = '';

if (!empty($goodsRecieptInfo)) {
    foreach ($goodsRecieptInfo as $gr) {
        $id = $gr->id;
        $code = $gr->code;
        $invoice_code = $gr->invoice_code;
        $invoice_date = $gr->invoice_date;
        $supplier_id = $gr->supplier_id;
        $delivery_date = $gr->delivery_date;
        $note = $gr->note;
        $status = $gr->status;
        $receipt_datetime = $gr->receipt_datetime;
        $mom_code = $gr->mom_code;
    }
}
?>

<style>
    .bordered-card {
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        padding: 15px;
    }

    .btn-custom {
        margin-right: 10px;
    }

    .form-title {
        margin-bottom: 30px;
        font-weight: bold;
    }

    .detail-section {
        margin-bottom: 20px;
    }

    .table-wrapper {
        margin-top: 20px;
    }

    .error-message {
        color: red;
        display: none;
    }
</style>

<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-list"></i> Chi Tiết Biên Bản Kiểm Nghiệm
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-purple">
                    <div class="box-tools">
                        <div class="box-body">
                            <input type="hidden" class="id" value="<?php echo $id; ?>">
                            <form id="receiptForm" action="<?php echo base_url('receipts/processForm/' . $id); ?>"
                                method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                                <div class="row">
                                    <div class="col-xs-12 text-right">
                                        <div class="form-group">
                                            <?php if ($status == 0): ?>
                                                <?php if ($role == ROLE_ADMIN || $role == ROLE_KCS) { ?>
                                                    <button type="submit" name="action" value="rating-process" <?php if (empty($goodsReceiptItems)): echo "disabled";
                                                                                                                endif; ?>
                                                        class="btn btn-success process-storage">
                                                        <i class="fa fa-check"></i> Tiến Hành Kiểm Tra
                                                    </button>
                                                <?php } ?>
                                            <?php elseif ($status == 1): ?>
                                                <?php if ($role == ROLE_ADMIN || $role == ROLE_KCS) { ?>
                                                    <button type="submit" name="action" value="rating-complete"
                                                        class="btn btn-success">
                                                        <i class="fa fa-check"></i> Hoàn Thành Kiểm Tra
                                                    </button>
                                                <?php } ?>
                                            <?php elseif ($status == 2): ?>
                                                <?php if ($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_EMPLOYEE) { ?>
                                                    <button type="submit" name="action" value="rating-comfirm"
                                                        class="btn btn-success">
                                                        <i class="fa fa-check"></i> Xác Nhận Kiểm Tra
                                                    </button>
                                                <?php } ?>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Mã Phiếu:</label>
                                            <input type="text" class="form-control" name="receipt_code"
                                                value="<?php echo htmlspecialchars($mom_code); ?>" readonly>
                                        </div>

                                        <div class="col-md-6">
                                            <label>Ngày Nhập:</label>
                                            <input type="text" class="form-control" name="receipt_datetime" readonly
                                                value="<?php echo htmlspecialchars($receipt_datetime ?? ''); ?>">
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Mã Chứng Từ:</label>
                                            <input type="text" class="form-control" name="invoice_code" readonly
                                                value="<?php echo htmlspecialchars($invoice_code ?? ''); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Ngày Chứng Từ:</label>
                                            <input type="date" class="form-control" name="document_date" readonly
                                                value="<?php echo date('Y-m-d', strtotime($invoice_date ?? '')); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Nhà Cung Cấp:</label>
                                            <select class="form-control" name="supplier_id" required disabled>
                                                <option value="">Chọn nhà cung cấp</option>
                                                <?php foreach ($suppliers as $supplier): ?>
                                                    <option value="<?php echo htmlspecialchars($supplier->id); ?>"
                                                        <?php echo (isset($supplier_id) && $supplier_id == $supplier->id) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($supplier->supplierName); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Ngày Dự Kiến Giao Hàng:</label>
                                            <input type="date" class="form-control" name="delivery_date" readonly
                                                value="<?php echo date('Y-m-d', strtotime($delivery_date ?? '')); ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Trạng Thái:</label>
                                            <input type="text" class="form-control" readonly value="<?php
                                                                                                    if ($status == 0) {
                                                                                                        echo "Đang Chờ Kiểm Tra";
                                                                                                    } elseif ($status == 1) {
                                                                                                        echo "Đang Kiểm Tra";
                                                                                                    } elseif ($status == 2) {
                                                                                                        echo "Đã Kiểm Tra";
                                                                                                    } elseif ($status == 3) {
                                                                                                        echo "Đang Lưu Kho";
                                                                                                    } elseif ($status == 4) {
                                                                                                        echo "Hoàn Thành";
                                                                                                    }
                                                                                                    ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Phiếu Nhập Kho:</label> <br />
                                            <a
                                                href="<?php echo base_url() ?>goods-receipts/detail/<?php echo $id ?>"><?php echo htmlspecialchars($code); ?></a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Ghi Chú:</label>
                                        <textarea class="form-control" rows="5" readonly
                                            name="note"><?php echo htmlspecialchars($note ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </form>
                        </div>


                        <!--<h3 class="form-title">Thông tin phiếu nhập</h3>-->

                        <table class="table table-bordered" id="selectedMaterialsTable">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã NVL</th>
                                    <th>Tên NVL</th>
                                    <th>Mã Kiện Hàng</th>
                                    <th>Số Lượng</th>
                                    <th>Đơn Vị</th>
                                    <th>Đánh GIá</th>
                                    <th>Ghi Chú</th>
                                    <?php if ($status == 1 && ($role == ROLE_ADMIN || $role == ROLE_KCS)): ?>
                                        <th>Hành Động</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($goodsReceiptItems)): ?>
                                    <?php foreach ($goodsReceiptItems as $index => $material): ?>
                                        <tr data-id="<?php echo $material->id; ?>" data-saved="false">
                                            <input type="hidden" class="material_id"
                                                value="<?php echo $material->material_id; ?>">
                                            <td><?php echo htmlspecialchars($index + 1); ?></td>
                                            <td><?php echo $material->materialCode; ?></td>
                                            <td><?php echo $material->materialName; ?></td>
                                            <td><?php echo $material->code; ?></td>
                                            <td><?php echo $material->quantity; ?></td>
                                            <td><?php echo $material->unit; ?></td>

                                            <td>
                                                <select <?php if ($status != 1): echo "disabled";
                                                        endif; ?> class="form-control rating">
                                                    <option value="0" <?php echo $material->rating == 0 ? 'selected' : ''; ?>>
                                                        Chờ Kiểm Tra</option>
                                                    <option value="1" <?php echo $material->rating == 1 ? 'selected' : ''; ?>>
                                                        Đạt</option>
                                                    <option value="2" <?php echo $material->rating == 2 ? 'selected' : ''; ?>>
                                                        Không Đạt</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control note"
                                                    value="<?php echo htmlspecialchars($material->note); ?>" <?php if ($status != 1): echo "readonly";
                                                                                                                endif; ?> />
                                            </td>
                                            <?php if ($status == 1 && ($role == ROLE_ADMIN || $role == ROLE_KCS)): ?>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-success rating-material">
                                                        <?php if ($material->rating == 0): ?>
                                                            <i class="fa fa-save"></i>
                                                        <?php else : ?>
                                                            <i class="fa fa-check-circle"></i>
                                                        <?php endif; ?>
                                                    </button>
                                                </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7">Không có dữ liệu nguyên vật liệu</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                </div>
                <script>
                    $(document).ready(function() {
                        localStorage.removeItem("savedMaterials");

                        const savedMaterialsKey = "savedMaterials";
                        let savedMaterials = [];

                        // Đánh dấu trạng thái từ cơ sở dữ liệu khi tải trang
                        $('#selectedMaterialsTable tbody tr').each(function() {
                            let id = $(this).data('id');
                            let rating = $(this).find('.rating').val();

                            if (rating !== "0") {
                                $(this).data('saved', true);
                                $(this).find('.rating-material').html('<i class="fa fa-check-circle"></i>');
                                savedMaterials.push(id);
                            }
                        });

                        // Cập nhật vào localStorage sau khi đánh dấu
                        localStorage.setItem(savedMaterialsKey, JSON.stringify(savedMaterials));

                        function checkAllRatings() {
                            let allRatedAndSaved = true;

                            $('#selectedMaterialsTable tbody tr').each(function() {
                                let rating = $(this).find('.rating').val();
                                let id = $(this).data('id');
                                let isSaved = savedMaterials.includes(id);

                                if (rating == "0" || !isSaved) {
                                    allRatedAndSaved = false;
                                    return false;
                                }
                            });

                            $('button[name="action"][value="rating-complete"]').prop('disabled', !allRatedAndSaved);
                        }

                        // Gọi hàm kiểm tra khi trang được tải
                        checkAllRatings();

                        $(document).on('change', '.rating', function() {
                            checkAllRatings();
                        });


                        $(document).on("click", ".rating-material", function() {
                            var row = $(this).closest('tr');
                            var id = row.data('id');
                            var note = row.find('.note').val();
                            var rating = row.find('.rating').val();
                            var hitURL = baseURL + "rating-material";
                            var success_mes = 'Cập nhật nguyên liệu thành công';
                            var error_mes = 'Cập nhật nguyên liệu thất bại';
                            var currentRow = $(this);

                            if (rating == "0") {
                                alert("Vui lòng đánh giá nguyên liệu trước khi lưu.");
                                return;
                            }

                            $.ajax({
                                type: "POST",
                                dataType: "json",
                                url: hitURL,
                                data: {
                                    id: id,
                                    rating: rating,
                                    note: note
                                }
                            }).done(function(data) {
                                if (data.status) {
                                    alert(success_mes);
                                    row.data('saved', true);
                                    currentRow.html('<i class="fa fa-check-circle"></i>');
                                    if (!savedMaterials.includes(id)) {
                                        savedMaterials.push(id);
                                        localStorage.setItem(savedMaterialsKey, JSON.stringify(savedMaterials));
                                    }

                                    checkAllRatings();
                                } else {
                                    alert(error_mes);
                                }
                            }).fail(function() {
                                alert(error_mes);
                            });
                        });
                    });
                </script>



            </div>
        </div>
    </section>
</div>