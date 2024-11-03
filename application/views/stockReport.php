<div class="content-wrapper">
    <section class="content-header">
        <h1>
            <i class="fa fa-lists"></i> Báo Cáo
        </h1>
    </section>
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-purple">
                    <div class="box-header">
                        <h3 class="box-title">Danh Sách Báo Cáo</h3>
                        <div class="box-tools">
                            <!-- <form action="<?php echo base_url('stock-report'); ?>" method="GET" id="searchList">
                                <div class="input-group">
                                    <input type="text" name="searchText" value="<?php echo isset($_GET['searchText']) ? htmlspecialchars($_GET['searchText']) : ''; ?>"
                                        class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search" />
                                    <div class="input-group-btn">
                                        <button type="submit" class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            </form> -->

                        </div>
                    </div>
                    <div class="box-body table-responsive no-padding">
                        <table class="table table-hover">
                            <tr>
                                <th>STT</th>
                                <th>Mã Nguyên Vật Liệu</th>
                                <th>Tên Nguyên Vật Liệu</th>
                                <th>Đơn Vị</th>
                                <th>Số Lượng Nhập</th>
                                <th>Số Lượng Xuất</th>
                                <th>Số Lượng Tồn Cuối</th>
                            </tr>
                            <?php
                            if (!empty($inventoryStockReport)) {
                                $i = 1;
                                foreach ($inventoryStockReport as $row) {
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $row->materialCode; ?></td>
                                <td><?php echo $row->materialName; ?></td>
                                <td><?php echo $row->unit; ?></td>
                                <td><?php echo $row->quantity_type1 ?? 0; ?></td>
                                <td><?php echo $row->quantity_type2 ?? 0; ?></td>
                                <td><?php echo $row->quantityAvailable; ?></td>

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