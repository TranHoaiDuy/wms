<?php

$id = '';
$code = '';
$re_code = '';
$factoryId =  '';
$exportDtm = '';
$createdBy = '';
$createdDtm = '';
$note = '';
$status = '';
if (!empty($receivingRequestInfo)) {
    foreach ($receivingRequestInfo as $gr) {
        $id = $gr->id;
        $code = $gr->code;
        $re_code = $gr->re_code;
        $factory_id = $gr->factoryId;
        $exportDtm = $gr->exportDtm;
        $createdBy = $gr->createdBy;
        $createdDtm = $gr->createdDtm;
        $note = $gr->note;
        $status = $gr->status;
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
            <i class="fa fa-list"></i> Chi Tiết Phiếu Nhận
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box box-purple">
                    <div class="box-tools">
                        <div class="box-body">
                            <input type="hidden" class="id" value="<?php echo $id; ?>">
                            <form id="receiptForm" action="<?php echo base_url('issue/processForm/' . $id); ?>"
                                method="POST">
                                <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>">
                                <div class="row">
                                    <div class="col-xs-12 text-right">
                                        <div class="form-group">
                                            <?php if ($status == 0): ?>
                                                <?php if ($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_PLANT) { ?>
                                                    <button type="submit" name="action" value="save"
                                                        class="btn btn-success btn-custom">
                                                        <i class="fa fa-save"></i> Lưu
                                                    </button>
                                                    <button type="submit" name="action" value="send-request"
                                                        class="btn btn-primary" id="createGoodsBtn">
                                                        <i class="fa fa-check"></i> Gởi Yêu Cầu
                                                    </button>
                                                <?php } ?>
                                            <?php endif; ?>
                                            <?php if ($status == 1 && $isCreateGI): ?>
                                                <?php if ($role == ROLE_ADMIN || $role == ROLE_KEEPER) { ?>
                                                    <button type="submit" name="action" value="create-goods"
                                                        class="btn btn-success" id="prepareGoodsBtn">
                                                        <i class="fa fa-check"></i> Tạo Phiếu Xuất
                                                    </button>
                                                <?php } ?>
                                            <?php endif; ?>
                                            <?php if ($status == 3): ?>
                                                <?php if ($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_PLANT) { ?>
                                                    <button type="submit" name="action" value="complete" class="btn btn-danger"
                                                        id="completeStorageBtn">
                                                        <i class="fa fa-check"></i> Xác Nhận Lấy Hàng
                                                    <?php } ?>
                                                    </button>
                                                <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="detail-section">
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="createBy">Mã Phiếu Nhận</label>
                                            <input type="text" class="form-control" name="re_code" id="re_code"
                                                value="<?php echo $re_code ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Phân Xưởng Yêu Cầu</label>
                                            <select class="form-control" name="factory_id" required <?php if ($status > 0): echo 'disabled';
                                                                                                    endif; ?>>
                                                <option value="">Chọn phân xưởng yêu cầu</option>
                                                <?php foreach ($factories as $fa): ?>
                                                    <option value="<?php echo htmlspecialchars($fa->id); ?>"
                                                        <?php echo (isset($factory_id) && $factory_id == $fa->id) ? 'selected' : ''; ?>>
                                                        <?php echo htmlspecialchars($fa->name); ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label for="createBy">Người Yêu Cầu</label>
                                            <input type="text" class="form-control" name="createBy" id="createBy"
                                                value="<?php echo $name ?>" readonly>
                                        </div>
                                        <div class="col-md-6">
                                            <label>Ngày Yêu Cầu</label>
                                            <input type="text" class="form-control" name="exportDtm" id="exportDtm"
                                                readonly value="<?php echo htmlspecialchars($exportDtm ?? ''); ?>">
                                            <span id="exportDtm-error" class="text-danger"></span>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <label>Trạng Thái:</label>
                                            <input type="text" class="form-control" readonly value="<?php
                                                                                                    if ($status == 0) {
                                                                                                        echo "Đang Khởi Tạo";
                                                                                                    } elseif ($status == 1) {
                                                                                                        echo "Đang Xử Lý";
                                                                                                    } elseif ($status == 2) {
                                                                                                        echo "Đang Kiểm Hàng";
                                                                                                    } elseif ($status == 3) {
                                                                                                        echo "Đang Xuất";
                                                                                                    } elseif ($status == 4) {
                                                                                                        echo "Hoàn Thành";
                                                                                                    }
                                                                                                    ?>">
                                        </div>
                                        <?php if ($status == 2): ?>
                                            <div class="col-md-6">
                                                <label>Phiếu Xuất Kho:</label> <br />
                                                <a
                                                    href="<?php echo base_url() ?>goods-issue/detail/<?php echo $id ?>"><?php echo htmlspecialchars($code); ?></a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="form-group">
                                        <label>Ghi Chú:</label>
                                        <textarea class="form-control" rows="5" <?php if ($status > 0): echo 'readonly';
                                                                                endif; ?>
                                            name="note"><?php echo htmlspecialchars($note ?? ''); ?></textarea>
                                    </div>
                                </div>
                            </form>

                            <?php if ($status == 0): ?>
                                <?php if ($role == ROLE_ADMIN || $role == ROLE_KEEPER || $role == ROLE_PLANT) { ?>
                                    <div class="form-group row">
                                        <!-- Danh Sách Yêu Cầu -->
                                        <div class="col-md-4">
                                            <label for="materials">Danh Sách Yêu Cầu</label>
                                            <select id="materials" name="materials" class="form-control select2" required <?php if ($status > 0): echo 'disabled';
                                                                                                                            endif; ?>>
                                                <option value="">Chọn nguyên vật liệu</option>
                                                <?php if (!empty($materials)): ?>
                                                    <?php foreach ($materials as $mate): ?>
                                                        <option value="<?php echo htmlspecialchars($mate['id']); ?>"
                                                            data-code="<?php echo htmlspecialchars($mate['materialCode']); ?>"
                                                            data-name="<?php echo htmlspecialchars($mate['materialName']); ?>"
                                                            data-unit="<?php echo htmlspecialchars($mate['unit']); ?>"
                                                            data-quantity="<?php echo htmlspecialchars($mate['quantity']); ?>">
                                                            <?php echo htmlspecialchars($mate['materialName']); ?>
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
                            <?php //echo var_dump($abcer); 
                            ?>
                            <table class="table table-bordered" id="selectedMaterialsTable">
                                <thead>
                                    <tr>
                                        <th>STT</th>
                                        <th>Mã Nguyên Vật Liệu</th>
                                        <th>Tên Nguyên Vật Liệu</th>
                                        <th>Số Lượng Kiện</th>
                                        <th>Số Lượng Yêu Cầu</th>
                                        <th>Đơn Vị</th>
                                        <?php if ($status == 0): ?>
                                            <th>Hành Động</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($goodsIssueItems)): ?>
                                        <?php foreach ($goodsIssueItems as $index => $material): ?>
                                            <tr data-id="<?php echo $material->id; ?>">
                                                <input type="hidden" class="material_id"
                                                    value="<?php echo $material->materialId; ?>">
                                                <input type="hidden" class="materialQuantity"
                                                    value="<?php echo $material->materialQuantity; ?>">

                                                <td><?php echo htmlspecialchars($index + 1); ?></td>
                                                <td><?php echo $material->materialCode; ?></td>
                                                <td><?php echo $material->materialName; ?></td>
                                                <td>
                                                    <?php if ($status == 0): ?>
                                                        <input type="number" class="form-control packing_quantity"
                                                            value="<?php echo $material->packing_quantity; ?>" />
                                                    <?php else: ?>
                                                        <input type="number" class="form-control packing_quantity"
                                                            value="<?php echo $material->packing_quantity; ?>" readonly />
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <input type="number" class="form-control material_quantity"
                                                        value="<?php echo $material->quantity; ?>" readonly />
                                                </td>
                                                <td><?php echo $material->unit; ?></td>
                                                <?php if ($status == 0): ?>
                                                    <td>
                                                        <button type=" button" class="btn btn-sm btn-success save-material">
                                                            <i class="fa fa-edit"></i></button>
                                                        <button type="button"
                                                            class="btn btn-danger btn-sm remove-material">Xóa</button>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function() {
                            let rowIndex = 1;
                            let selectedMaterials = new Set();
                            toggleSubmitButton();

                            function updateRowIndex() {
                                $('#selectedMaterialsTable tbody tr').each(function(index) {
                                    console.log(index);
                                    $(this).find('td:first').text(index + 1);
                                });
                            }

                            jQuery(document).on("change", ".packing_quantity", function() {
                                var row = (this).closest('tr');
                                materialQuantity = row.querySelector('.materialQuantity').value;
                                packQuantity = row.querySelector('.packing_quantity').value;
                                row.querySelector('.material_quantity').value = materialQuantity * packQuantity
                                console.log(materialQuantity);
                            });

                            $('#materials').change(function() {
                                const selectedOption = $(this).find('option:selected');
                                const id = selectedOption.val();
                                const code = selectedOption.data('code');
                                const name = selectedOption.data('name');
                                const unit = selectedOption.data('unit');
                                const quantity = selectedOption.data('quantity');
                                if (id) {
                                    if (!selectedMaterials.has(id)) {
                                        $('#selectedMaterialsTable tbody').append(`
                                        <tr data-id="0">
                                           
                                            <td>${rowIndex}</td>
                                            <input type="hidden" class="form-control materialQuantity" value="${quantity}" />
                                            <td>${code}</td>
                                            <td>${name}</td>
                                            <td> <input type="number" class="form-control packing_quantity"
                                                value="1" /></td>
                                            <td><input type="number" class="form-control material_quantity" value="${quantity}" /></td>
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
                                        toggleSubmitButton();
                                        $(this).val('').trigger('change');

                                    } else {
                                        alert('Nguyên vật liệu này đã được chọn!');
                                    }
                                }
                            });

                            jQuery(document).on("click", ".save-material", function() {
                                var row = (this).closest('tr');
                                var grId = document.querySelector('.id').value;
                                id = row.getAttribute('data-id');
                                hitURL = baseURL + "add-material-issue";
                                materialId = row.querySelector('.material_id').value;
                                quantity = row.querySelector('.material_quantity').value;
                                packQuantity = row.querySelector('.packing_quantity').value;
                                currentRow = $(this);
                                success_mes = 'Lưu nguyên vật này thành công';
                                error_mes = 'Nguyên vật đã tồn tại, hãy cập nhật số kiện';
                                if (id > 0) {
                                    hitURL = baseURL + "edit-material-issue";
                                    success_mes = 'Cập nhât nguyên liệu thành công';
                                    error_mes = 'Cập nhât nguyên liệu thành công';
                                }

                                // console.log(grId);
                                jQuery.ajax({
                                    type: "POST",
                                    dataType: "json",
                                    url: hitURL,
                                    data: {
                                        id: id,
                                        goods_issue_id: grId,
                                        material_id: materialId,
                                        material_quantity: quantity,
                                        packQuantity: packQuantity
                                    }
                                }).done(function(data) {
                                    if (data.status = true) {
                                        alert(success_mes);
                                        $(row).attr('data-id', data.id);
                                        currentRow.html('<i class="fa fa-edit"></i>');
                                        toggleSubmitButton();
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
                                const hitURL = baseURL + "delete-material-issue";


                                if (id === 0) {

                                    if (confirm('Bạn có muốn xóa nguyên vật liệu chưa lưu này không?')) {
                                        row.remove();
                                        updateRowIndex();
                                        selectedMaterials.delete(materialId);
                                        toggleSubmitButton();
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
                                                toggleSubmitButton();
                                            } else {
                                                alert('Xóa nguyên liệu thất bại');
                                            }
                                        }).fail(function() {
                                            alert('Có lỗi xảy ra. Vui lòng thử lại.');

                                        });
                                    }
                                }
                            });

                            function toggleSubmitButton() {
                                const submitButton = $('#createGoodsBtn');
                                if ($('#selectedMaterialsTable tbody tr').length > 0 && checkAllRowsSaved()) {
                                    submitButton.prop('disabled', false);
                                } else {
                                    submitButton.prop('disabled', true);
                                }
                            }

                            function checkAllRowsSaved() {
                                let allRowsSaved = true;
                                $('#selectedMaterialsTable tbody tr').each(function() {
                                    if ($(this).attr('data-id') == "0") {
                                        allRowsSaved = false;
                                        return false;
                                    }
                                });
                                return allRowsSaved;
                            }
                            $('#createGoodsBtn').on('click', function(event) {
                                if (!checkAllRowsSaved()) {
                                    event.preventDefault();
                                    // alert('Vui lòng lưu tất cả các nguyên vật liệu trước khi gửi!');
                                }
                            });
                        });
                    </script>

                    <script>
                        function validateDates() {
                            var exportDtm = document.getElementById("exportDtm").value;
                            var today = new Date().toISOString().split('T')[0];
                            document.getElementById("exportDtm-error").innerText = "";

                            let isValid = true;
                            if (exportDtm < today) {
                                document.getElementById("exportDtm-error").innerText =
                                    "Ngày yêu cầu xuất phải sau ngày hiện tại.";
                                isValid = false;
                            }

                            return isValid;
                        }
                    </script>

                </div>
            </div>
        </div>
    </section>
</div>