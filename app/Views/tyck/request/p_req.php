<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>仓储管理系统</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="<?= base_url(); ?>/plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?= base_url(); ?>/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url('/'); ?>/fonts/font.css">
    <?= $this->renderSection('style') ?>
    <style>
        .page-break {
            page-break-before: always;
        }

        .report-req {
            font-size: 14px;
        }


        .table-print thead tr {
            border-style: solid;
        }

        .table-print td {
            border-style: solid;
            border-color: gray;
            text-align: center;
            white-space: nowrap;
        }

        .header {
            text-align: center;
        }

        .kolom {
            text-align: center;
            white-space: nowrap;
        }

        .footer {
            font-size: 16px;
        }

        @media print {

            .report-req {
                font-size: 18px;
            }
        }

        @page {
            size: 8.5in 5.5in;
            margin: 5mm 10mm 5mm 5mm;
            /* change the margins as you want them to be. */
        }
    </style>
</head>

<body style="width: 8.5in;">
    <div class="wrapper m-0">
        <?php
        $row_count = count($rows);
        $max_row = 5;
        $page_num = ceil($row_count / $max_row);
        ?>

        <section class="invoice report-req">
            <!-- title row -->
            <?php for ($i = 1; $i <= $page_num; $i++) : ?>
                <div class="font-weight-bold">
                    <div class="<?= ($i <> 1) ? 'd-none d-print-block page-break' : '' ?> pt-3">
                        <div class="row mb-1 ">
                            <div class="col-12 ">
                                <h2 class="row">
                                    <div class="col-auto">
                                        <img src="<?= base_url('/img') . '/' . $cmp[0]['logo']; ?>" alt="logo" class="brand-image" width="120">
                                    </div>
                                    <div class="col-10">
                                        <p class="brand-text h2 font-weight-bold header"><?= $cmp[0]['nameInd'] . ' ' . $cmp[0]['nameMan']; ?></p>
                                        <p class="brand-text h4 font-weight-bold header">Surat Permintaan Pengambilan Barang</p>
                                        <p class="brand-text h4 font-weight-bold header">领用申请单</p>
                                        <button class="btn btn-primary d-print-none float-right" onclick="window.print()">
                                            <i class="fas fa-print"></i>
                                            打印
                                            Print
                                        </button>
                                    </div>
                                </h2>
                            </div>
                            <!-- /.col -->
                        </div>

                        <div class="row">
                            <div class="col-5">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td>No. 单据编号</td>
                                        <td>: </td>
                                    </tr>
                                    <tr>
                                        <td>Dept.Pengambil 领用部门</td>
                                        <td>: <?= $header['req_dept']; ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-1"></div>
                            <div class="col-6">
                                <table class="table table-sm table-borderless">
                                    <tr>
                                        <td>Tanggal 日期</td>
                                        <td>: <?= $header['req_date']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Requestor 申请人</td>
                                        <td>: <?= $header['req_name']; ?></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php if ($i == 1) : ?>
                        <div class="row d-print-none">
                            <div class="col">
                                <table class="table table-sm table-print">
                                    <thead>
                                        <tr>
                                            <td class="font-weight-bold kolom">BIN LOCATION<br>库位</td>
                                            <td class="font-weight-bold kolom">KODE BAHAN<br>物料代码</td>
                                            <td class="font-weight-bold kolom">NAMA BAHAN<br>物料名称</td>
                                            <td class="font-weight-bold kolom">SATUAN<br>单位</td>
                                            <td class="font-weight-bold kolom">JUMLAH<br>数量</td>
                                            <td class="font-weight-bold kolom">JUMLAH<br>实发数量</td>
                                            <td class="font-weight-bold kolom">KEGUNAAN<br>用途</td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($rows as $row) : ?>
                                            <tr>
                                                <td><?= $row['location']; ?></td>
                                                <td><?= $row['goods_id']; ?></td>
                                                <td><?= $row['name_type']; ?></td>
                                                <td><?= $row['unit']; ?></td>
                                                <td><?= $row['qty']; ?></td>
                                                <td></td>
                                                <td><?= $row['useFor']; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="row d-none d-print-block">
                        <div class="col">
                            <table class="table table-sm table-print">
                                <thead>
                                    <tr>
                                        <td class="font-weight-bold kolom">BIN LOCATION<br>库位</td>
                                        <td class="font-weight-bold kolom">KODE BAHAN<br>物料代码</td>
                                        <td class="font-weight-bold kolom">NAMA BAHAN<br>物料名称</td>
                                        <td class="font-weight-bold kolom">SATUAN<br>单位</td>
                                        <td class="font-weight-bold kolom">JUMLAH<br>数量</td>
                                        <td class="font-weight-bold kolom">JUMLAH<br>实发数量</td>
                                        <td class="font-weight-bold kolom">KEGUNAAN<br>用途</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php for ($j = 0; $j < ($page_num == $i ? (($row_count % $max_row == 0) ? $max_row : $row_count % $max_row) : $max_row); $j++) : ?>
                                        <tr>
                                            <td><?= $rows[$j + (($i - 1) * $max_row)]['location']; ?></td>
                                            <td><?= $rows[$j + (($i - 1) * $max_row)]['goods_id']; ?></td>
                                            <td><?= $rows[$j + (($i - 1) * $max_row)]['name_type']; ?></td>
                                            <td><?= $rows[$j + (($i - 1) * $max_row)]['unit']; ?></td>
                                            <td><?= $rows[$j + (($i - 1) * $max_row)]['qty']; ?></td>
                                            <td></td>
                                            <td><?= $rows[$j + (($i - 1) * $max_row)]['useFor']; ?></td>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="<?= ($i <> $page_num) ? 'd-none d-print-block' : '' ?>">
                        <div class="row mb-3">
                            <div class="col-3">
                                Diminta oleh.申请人:
                            </div>
                            <div class="col"></div>
                            <div class="col-3">
                                Kepala Dept.部门审核人:
                            </div>
                            <div class="col"></div>
                            <div class="col-4">
                                Pengurus Gudang.仓库:
                            </div>
                        </div>
                    </div>
                </div>
                <div style="position: fixed; height:45px; bottom: 0;">
                    <div class="footer">Note：Warehouse Dept(white copy);Requester Dept(red copy);Finance Dept (yellow copy) 备注：
                        <br>
                        一式三份，统计（白联），领用部门（红联），仓库（黄联）
                    </div>
                </div>
            <?php endfor; ?>
        </section>
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="<?= base_url(); ?>/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="<?= base_url(); ?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- AdminLTE App -->
    <script src="<?= base_url(); ?>/dist/js/adminlte.min.js"></script>
    <!-- Moment.js -->
    <script src="<?= base_url(); ?>/plugins/moment/moment.min.js"></script>
    <script>
        // window.addEventListener("load", window.print());
    </script>
</body>

</html>