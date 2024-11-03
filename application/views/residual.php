<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-lists"></i> Quản Lý Tồn Dư
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-purple">
                    <div class="box-header">
                        <h3 class="box-title">Danh Sách Tồn Dư</h3>
                        <div class="box-tools">
                            <form action="<?php echo base_url('inventory'); ?>" method="GET" id="searchList">
                                <div class="input-group">
                                    <input type="text" name="searchText" value="<?php echo isset($_GET['searchText']) ? htmlspecialchars($_GET['searchText']) : ''; ?>"
                                        class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search" />
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>Tên Nguyên Vật Liệu</th>
                                <th>Số Lượng Tồn Kho</th>
                                <th>Đơn Vị</th>
                            </tr>
                            <?php
                            if (!empty($getInventory)) {
                                foreach ($getInventory as $inven) {
                            ?>
                                    <tr onclick="window.location.href='<?php echo base_url('inventory/detail/' . $inven['materialId']); ?>'">
                                        <input type="hidden" name="materialId" value="<?php echo $inven['materialId']; ?>" />
                                        <td><?php echo htmlspecialchars($inven['materialName']); ?></td>
                                        <td><?php echo $inven['quantityAvailable']; ?></td>
                                        <td><?php echo htmlspecialchars($inven['unit']); ?></td>

                                    </tr>
                            <?php }
                            } ?>
                        </table>

                    </div>
                </div>
            </div>
        </div>
    </section>
</div>