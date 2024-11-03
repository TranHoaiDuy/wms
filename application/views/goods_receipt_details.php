<?php

$id = '';
$code = '';
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
.text-danger {
    color: red;
    font-size: 12px;
}

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
            <i class="fa fa-list"></i> Chi Tiết Phiếu Nhập Kho
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
                                method="POST" onsubmit="return validateDates()">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                                <div class="row">
                                    <div class="col-xs-12 text-right">
                                        <div class="form-group">
                                            <?php if ($status == 0): ?>
                                            <?php if($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_PLAN ) { ?>
                                            <button type="submit" name="action" value="save"
                                                class="btn btn-success btn-custom">
                                                <i class="fa fa-save"></i> Lưu
                                            </button>
                                            <?php } ?>
                                            <?php  elseif ($status == 3): ?>
                                            <?php if($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_EMPLOYEE ) { ?>
                                            <!-- <button type="submit" name="action" value="export-QRCode"
                                                    class="btn btn-primary">
                                                    <i class="fa fa-check"></i> In QRCode
                                                </button> -->
                                            <button id="printQRCodeBtn" class="btn btn-primary">In QRCode</button>
                                            <button type="submit" name="action" value="save" class="btn btn-success">
                                                <i class="fa fa-save"></i> Lưu
                                            </button>
                                            <button type="submit" name="action" value="storage-complete"
                                                class="btn btn-danger" id="completeStorageBtn"
                                                <?php if (!$exists) echo 'disabled'; ?>>
                                                <i class="fa fa-check"></i> Hoàn Thành Lưu Kho
                                            </button>
                                            <?php } endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Mã Phiếu:</label>
                                            <input type="text" class="form-control" name="code"
                                                value="<?php echo htmlspecialchars($code); ?>" readonly>
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
                                            <input type="text" class="form-control" name="invoice_code" required <?php if ($status > 0): echo 'readonly';
                                                endif; ?> value="<?php echo htmlspecialchars($invoice_code ?? ''); ?>">
                                        </div>
                                        <div class="col-md-6">
                                            <label>Ngày Chứng Từ:</label>
                                            <input type="date" class="form-control" name="invoice_date"
                                                id="ngay-chung-tu" required <?php if ($status > 0): echo 'readonly';
                                                                            endif; ?>
                                                value="<?php echo date('Y-m-d', strtotime($invoice_date ?? '')); ?>">
                                            <span id="invoice-date-error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Nhà Cung Cấp:</label>
                                            <select class="form-control" name="supplier_id" required <?php if ($status > 0): echo 'disabled';
                                                                                                        endif; ?>>
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
                                            <input type="date" class="form-control" name="delivery_date"
                                                id="ngay-du-kien" required <?php if ($status > 0): echo 'readonly';
                                                                            endif; ?>
                                                value="<?php echo date('Y-m-d', strtotime($delivery_date ?? '')); ?>">
                                            <span id="delivery-date-error" class="text-danger"></span>
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
                                            <label>Biên Bản Kiểm Nghiệm:</label> <br />
                                            <a
                                                href="<?php echo base_url() ?>mom-reports/detail/<?php echo $id ?>"><?php echo htmlspecialchars($mom_code); ?></a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>Ghi Chú:</label>
                                        <textarea class="form-control" rows="5" <?php if ($status > 3): echo 'readonly';
                                                                                endif; ?>
                                            name="note"><?php echo htmlspecialchars($note ?? ''); ?></textarea>
                                    </div>

                            </form>
                        </div>


                        <!--<h3 class="form-title">Thông tin phiếu nhập</h3>-->
                        <?php if ($status == 0): ?>
                        <?php if($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_PLAN ) { ?>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <select id="materials" name="materials" class="form-control select2">
                                    <option value="">Chọn nguyên vật liệu</option>
                                    <?php if (!empty($materials)): ?>
                                    <?php foreach ($materials as $mate): ?>
                                    <option value="<?php echo htmlspecialchars($mate['id']); ?>"
                                        data-code="<?php echo htmlspecialchars($mate['materialCode']); ?>"
                                        data-name="<?php echo htmlspecialchars($mate['materialName']); ?>"
                                        data-unit="<?php echo htmlspecialchars($mate['unit']); ?>"
                                        data-quantity="<?php echo htmlspecialchars($mate['quantity']); ?>">
                                        <?php echo htmlspecialchars($mate['materialCode'] . ' - ' . $mate['materialName']); ?>
                                    </option>
                                    <?php endforeach; ?>
                                    <?php else: ?>
                                    <option value="">Không có nguyên vật liệu</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <?php } ?>
                        <?php endif; ?>
                        <table class="table table-bordered" id="selectedMaterialsTable">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã NVL</th>
                                    <th>Tên NVL</th>
                                    <th>Mã Kiện Hàng</th>
                                    <th>Số Lượng</th>
                                    <th>Đơn Vị</th>
                                    <?php if ($status == 0): ?>
                                    <?php if($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_PLAN ) { ?>
                                    <th>Hành Động</th>
                                    <?php } ?>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>

                                <?php if (!empty($goodsReceiptItems)): ?>
                                <?php foreach ($goodsReceiptItems as $index => $material): ?>
                                <tr data-id="<?php echo $material->id; ?>">
                                    <input type="hidden" class="material_id"
                                        value="<?php echo $material->material_id; ?>">
                                    <td><?php echo htmlspecialchars($index + 1); ?></td>
                                    <td><?php echo $material->materialCode; ?></td>
                                    <td><?php echo $material->materialName; ?></td>
                                    <td>
                                        <?php if ($status == 0 && ($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_PLAN )): ?>
                                        <input type="text" class="form-control material_code"
                                            value="<?php echo $material->code; ?>" />
                                        <?php else:
                                                    echo $material->code;
                                                endif; ?>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control material_quantity" readonly
                                            value="<?php echo $material->quantity; ?>" />
                                    </td>
                                    <td><?php echo $material->unit; ?></td>

                                    <?php if ($status == 0 && ($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_PLAN )): ?>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-success save-material">
                                            <i class="fa fa-edit"></i></button>
                                        <button type="button" class="btn btn-danger btn-sm remove-material">Xóa</button>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <!-- <tr>
                                            <td colspan="7">Không có dữ liệu nguyên vật liệu</td>
                                        </tr> -->
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>



                    <!-- Modal QRCode -->
                    <div class="modal fade" id="QRCodeModal" tabindex="-1" role="dialog"
                        aria-labelledby="QRCodeModalLabel" aria-hidden="true">

                        <div class="modal-dialog modal-lg" role="document">

                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header bg-primary text-white">
                                    <center>
                                        <h4 class="modal-title" id="QRCodeModalLabel">Gán Vị Trí và Xuất QRCode</h4>
                                    </center>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>

                                <!-- Modal Body -->
                                <div class="modal-body">
                                    <form id="qrCodeForm" method="post"
                                        action="<?php echo base_url('receipts/generate'); ?>">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-hover table-bordered table-sm">
                                                <thead class="thead-light">
                                                    <tr>
                                                        <th scope="col">STT</th>
                                                        <th scope="col">Mã NVL</th>
                                                        <th scope="col">Tên NVL</th>
                                                        <th scope="col">Mã Kiện Hàng</th>
                                                        <th scope="col">Số Lượng</th>
                                                        <th scope="col">Đơn Vị</th>
                                                        <th scope="col">Vị Trí Gợi ý</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="materialTableBody">
                                                    <?php if (!empty($goodsReceiptItems)): ?>
                                                    <?php foreach ($goodsReceiptItems as $index => $material): ?>
                                                    <tr data-id="<?php echo $material->id; ?>">
                                                        <input type="hidden"
                                                            name="materials[<?php echo $material->id; ?>][receipt_datetime]"
                                                            value="<?php echo htmlspecialchars($receipt_datetime ?? ''); ?>">
                                                        <input type="hidden"
                                                            name="materials[<?php echo $material->id; ?>][id]"
                                                            value="<?php echo $material->id; ?>">
                                                        <input type="hidden"
                                                            name="materials[<?php echo $material->id; ?>][materialName]"
                                                            value="<?php echo $material->materialName; ?>">
                                                        <input type="hidden"
                                                            name="materials[<?php echo $material->id; ?>][qrcode_item]"
                                                            value="<?php echo $material->qrcode_item; ?>">
                                                        <input type="hidden"
                                                            name="materials[<?php echo $material->id; ?>][locationId]"
                                                            value="<?php echo $material->locationId; ?>">
                                                        <input type="hidden"
                                                            name="materials[<?php echo $material->id; ?>][cargo_area]"
                                                            value="<?php echo $material->cargo_area; ?>">
                                                        <input type="hidden"
                                                            name="materials[<?php echo $material->id; ?>][cargo_location]"
                                                            value="<?php echo $material->cargo_location; ?>">
                                                        <input type="hidden"
                                                            name="materials[<?php echo $material->id; ?>][code]"
                                                            value="<?php echo $material->code; ?>">
                                                        <td><?php echo htmlspecialchars($index + 1); ?></td>
                                                        <td><?php echo htmlspecialchars($material->materialCode); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($material->materialName); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($material->code); ?></td>
                                                        <td><?php echo htmlspecialchars($material->quantity); ?>
                                                        </td>
                                                        <td><?php echo htmlspecialchars($material->unit); ?></td>

                                                        <!-- <?php echo var_dump($material) ?> -->

                                                        <?php if (!empty($material->locationId)): ?>
                                                        <td> <?php echo $material->cargo_area . $material->cargo_location; ?>
                                                        </td>
                                                        <?php else: ?>
                                                        <td>
                                                            <select class="form-control position-suggestion"
                                                                name="materials[<?php echo $material->id; ?>][location_id]"
                                                                data-material-id="<?php echo $material->material_id; ?>">
                                                                <option value="0">Chọn vị trí</option>
                                                                <?php if (!empty($locationsByStatus[$material->material_id])): ?>
                                                                <?php foreach ($locationsByStatus[$material->material_id] as $location): ?>
                                                                <option
                                                                    value="<?php echo htmlspecialchars($location->id); ?>">
                                                                    <?php echo htmlspecialchars($location->cargo_area . $location->cargo_location); ?>
                                                                </option>
                                                                <?php endforeach; ?>
                                                                <?php endif; ?>
                                                            </select>
                                                        </td>
                                                        <?php endif; ?>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                    <?php else: ?>
                                                    <tr>
                                                        <td colspan="6" class="text-center">Không có dữ liệu nguyên
                                                            vật liệu</td>
                                                    </tr>
                                                    <?php endif; ?>
                                                </tbody>

                                            </table>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-dismiss="modal">Đóng</button>
                                            <button type="submit" name="action" value="export-QRCode"
                                                class="btn btn-primary">
                                                <i class="fa fa-check"></i> In QRCode
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
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
                        document.getElementById("invoice-date-error").innerText =
                            "Ngày chứng từ phải trước ngày hiện tại.";
                        isValid = false;
                    }
                    if (deliveryDate < today) {
                        document.getElementById("delivery-date-error").innerText =
                            "Ngày dự kiến giao hàng phải sau ngày hiện tại.";
                        isValid = false;
                    }

                    return isValid;
                }
                </script>
                <script>
                $(document).ready(function() {
                    let rowIndex = 1;
                    let selectedMaterials = new Set();

                    function updateRowIndex() {
                        $('#selectedMaterialsTable tbody tr').each(function(index) {
                            console.log(index);
                            $(this).find('td:first').text(index + 1);
                        });
                    }

                    $('#materials').change(function() {
                        const selectedOption = $(this).find('option:selected');
                        const id = selectedOption.val();
                        const code = selectedOption.data('code');
                        const name = selectedOption.data('name');
                        const unit = selectedOption.data('unit');
                        const quantity = selectedOption.data('quantity');
                        if (id) {
                            //if (!selectedMaterials.has(id)) {
                            $('#selectedMaterialsTable tbody').append(`
                                        <tr data-id="0">
                                            <td>${rowIndex}</td>
                                            <td>${code}</td>
                                            <td>${name}</td>
                                            <td><input type="text" class="form-control material_code" value="" /></td>
                                            <td><input type="numb)er" class="form-control material_quantity" value="${quantity}" readonly /></td>
                                            <input type="hidden" class="material_id" value="${id}">
                                            <td>${unit}</td>
                                           
                                            <td> <button  type="button" class="btn btn-sm btn-success save-material">
                                                <i class="fa fa-save"></i></button>
                                                <button type="button" class="btn btn-danger btn-sm remove-material">Xóa</button></td>
                                        </tr>
                                    `);
                            selectedMaterials.add(id);
                            updateRowIndex();
                            rowIndex++;
                            $(this).val('').trigger('change');
                            //} else {
                            //   alert('Nguyên vật liệu này đã được chọn!');
                            //}
                        }
                    });

                    jQuery(document).on("click", ".save-material", function() {
                        var row = (this).closest('tr');
                        var grId = document.querySelector('.id').value;
                        id = row.getAttribute('data-id');
                        hitURL = baseURL + "add-material";
                        materialId = row.querySelector('.material_id').value;
                        quantity = row.querySelector('.material_quantity').value;
                        code = row.querySelector('.material_code').value;
                        if (!code) {
                            alert("Mã kiện không được để trống.");
                            return;
                        }

                        if (id > 0) {
                            hitURL = baseURL + "edit-material";
                            success_mes = 'Cập nhât nguyên liệu thành công';
                            error_mes = 'Cập nhât nguyên liệu thành công';
                        } else {
                            currentRow = $(this);
                            success_mes = 'Lưu nguyên vật này thành công';
                            error_mes = 'Lưu nguyên vật này thất bại hoặc đã tồn tại';
                        }
                        // console.log(grId);

                        jQuery.ajax({
                            type: "POST",
                            dataType: "json",
                            url: hitURL,
                            data: {
                                id: id,
                                goods_receipt_id: grId,
                                material_id: materialId,
                                material_quantity: quantity,
                                code: code
                            }
                        }).done(function(data) {
                            if (data.status = true) {
                                alert(success_mes);
                                $(row).attr('data-id', data.id);
                                currentRow.html('<i class="fa fa-edit"></i>');
                                $('.process-storage').removeAttr("disabled")
                            } else if (data.status = false) {
                                alert(error_mes);
                            } else {
                                alert("Access denied..!");
                            }
                        }).fail(function(data) {
                            alert(error_mes);
                        })
                    });
                    jQuery(document).on('click', '.remove-material', function() {
                        const row = $(this).closest('tr');
                        const materialId = row.find('.material_id').val();
                        const id = row.data('id');
                        const hitURL = baseURL + "delete-material";


                        if (id === 0) {

                            if (confirm('Bạn có muốn xóa nguyên vật liệu chưa lưu này không?')) {
                                row.remove();
                                updateRowIndex();
                                selectedMaterials.delete(materialId);
                            }
                        } else {
                            if (confirm('Bạn có chắc chắn muốn xóa nguyên vật liệu này không?')) {
                                jQuery.ajax({
                                    type: "POST",
                                    dataType: "json",
                                    url: hitURL,
                                    data: {
                                        id: id
                                    }
                                }).done(function(data) {
                                    if (data.status === true) {
                                        row.remove();
                                        updateRowIndex();
                                        selectedMaterials.delete(materialId);
                                    } else {
                                        alert('Xóa nguyên liệu thất bại');
                                    }
                                }).fail(function() {
                                    alert('Có lỗi xảy ra. Vui lòng thử lại.');

                                });
                            }
                        }
                    });
                });
                </script>
                <script>
                $(document).ready(function() {
                    // Khi nút "In QRCode" được nhấn
                    $('#printQRCodeBtn').click(function(event) {
                        event.preventDefault();
                        $('#QRCodeModal').modal('show');
                    });
                    // Xử lý sự kiện gửi form QR Code
                    $('#qrCodeForm').on('submit', function(event) {
                        var positionDropdowns = $('.position-suggestion');
                        var selectedPositions = [];
                        var unselected = false;
                        if (!isMappedSuccessfully) {
                            $('#completeStorageBtn').prop('disabled', true);
                        }
                        // Kiểm tra vị trí đã chọn
                        positionDropdowns.each(function() {
                            var selectedValue = $(this).val();
                            if (selectedValue === "0") {
                                unselected = true;
                            } else {
                                selectedPositions.push(selectedValue);
                            }
                        });
                        // Kiểm tra xem tất cả các vị trí đã được chọn hay chưa
                        if (unselected) {
                            event.preventDefault();
                            alert("Bạn chưa map vị trí cho tất cả nguyên vật liệu.");
                            $('#printQRCodeBtn').prop('disabled', false);
                        } else {
                            $('#completeStorageBtn').prop('disabled', false);
                            $('#QRCodeModal').modal('hide'); // Đóng popup
                            isMappedSuccessfully = true;
                        }
                    });

                    // Kiểm tra trùng lặp ngay khi người dùng thay đổi chọn vị trí
                    $('.position-suggestion').change(function() {
                        var currentDropdown = $(this);
                        var selectedValue = currentDropdown.val();
                        var duplicateFound = false;

                        // Kiểm tra xem vị trí đã được chọn trước đó chưa
                        if (selectedValue !== "0") {
                            $('.position-suggestion').not(currentDropdown).each(function() {
                                if ($(this).val() === selectedValue) {
                                    duplicateFound = true;
                                    return false; // Dừng vòng lặp khi phát hiện trùng lặp
                                }
                            });

                            // Nếu có vị trí trùng lặp, thông báo và reset dropdown
                            if (duplicateFound) {
                                alert("Vị trí này đã được chọn rồi. Vui lòng chọn vị trí khác!");
                                currentDropdown.val("0"); // Reset lại dropdown hiện tại
                            }
                        }
                    });
                    var isMappedSuccessfully = false; // Biến để theo dõi trạng thái map thành công

                });
                </script>

            </div>
        </div>
    </section>
</div>