<?php
    use Elasticsearch\ClientBuilder;
    /**
     * Connect to ElasticSearch server
     * Create/delete index: product
     */

    require "./vendor/autoload.php";

    $hosts = [
        [
            'host' => '127.0.0.1',
            'port' => '9200',
            'scheme' => 'http'
        ]
    ];

    $client = ClientBuilder::create()->setHosts($hosts)->build();

    $exists = $client->indices()->exists(['index' => 'product']);
    $flagS = $_POST['flagSearch'] ?? null;
    if ($flagS)
        $search = $_POST['search'] ?? null;
    $flagP = $_POST['flagPrice'] ?? null;
    if ($flagP)
        $max = $_POST['max'] ?? null;
        $min = $_POST['min'] ?? null;
    $msg = "";
    if ($flagS || $flagP) {
        $items = null;
        $total = 0;
        $msg = "No thing is found!";

        if (TRUE) {
            $params = [
                'index' => 'product',
                'type' => 'product_type',
                'body' => [
                    'query' => [
                        'bool' => [
                            'should'=> [
                                ['match' =>['name' => $search]],
                                ['match' =>['des' => $search]],
                                ['match' => ['tags' => $search]]
                            ]
                        ]
                    ]
                ]
            ];
            try {
                $results = $client->search($params);
                $total1 = $results['hits']['total']['value'];
            } catch (\Throwable $th) {
                $results = null;            
            }
                
        }

        if ($total > 0) {
            $items = $results['hits']['hits'];
            if ($total == 1)
                $msg = $total." item is found.";
            else
                $msg = $total." items are found.";
        }
        
    }
    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link href="./css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="./css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./ks/ion.rangeSlider.min.css">
    <link rel="stylesheet" href="./js/bootstrap-slider.min.css">
</head>
<body>
    <div id="wrapper">

        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                <div class="sidebar-brand-icon">
                    <i><img src="./img/hcmut.png" alt="Logo branch" width="50px"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Elastic S.</div>
            </a>

            <hr class="sidebar-divider my-0">

            <li class="nav-item">
                <a class="nav-link" href="update.php">
                    <i class="fa fa-plus" aria-hidden="true"></i>
                    <span>Update</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="delete.php">
                    <i class="fa fa-trash" aria-hidden="true"></i>
                    <span>Delete</span>
                </a>
            </li>
            <li class="nav-item active">
                <a class="nav-link" href="search.php">
                    <i class="fa fa-filter" aria-hidden="true"></i>
                    <span>Filter product</span>
                </a>
            </li>
        </ul>

        <div id="content-wrapper" class="d-flex flex-column">

            <div id="content">

                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars" aria-hidden="true"></i>
                    </button>

                </nav>

                <div class="container-fluid" width="80%">
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Search</h3>
                                        </div>
                                        <form role="form" id="quickForm" action="#" method="post">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <input type="checkbox" id="flagSearch" name="flagSearch">
                                                    <label for="search">Search</label>
                                                    <input type="text" name="search" class="form-control" id="search" placeholder="Search for ..." value="<?=$search?>">
                                                </div>
                                                <div class="form-group">
                                                    <input type="checkbox" id="flagPrice" name="flagPrice">
                                                    <label>Price: </label><br>
                                                    <label for="min">From</label>
                                                    <input type="number" id="min" name="min" width="100px" value="<?=$min?>" placeholder="Enter min value">
                                                    <label for="max">To</label>
                                                    <input type="number" id="max" name="max" width="100px" value="<?=$max?>" placeholder="Enter max value">
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
                                            <div class ="card-body text-success"><i><?=$msg?></i></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <?php if($items != null): ?>
                            <div class="row">
                                <?php foreach($items as $item): ?>
                                <div class="col-md-12">
                                    <div class="card card-primary">
                                        <div class="card-body">
                                            <?php
                                                $name = $item['_source']['name'];
                                                $price = $item['_source']['price'];
                                                $tags = implode(',',$item['_source']['tags']);
                                            ?>
                                            <div><?=$name?></div>
                                            <div><?=$price?></div>
                                            <div><?=$tags?></div>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach ?>
                            </div>
                            <?php endif ?>
                        </div>
                    </section>
                </div>


            </div>

            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                <div class="copyright text-center my-auto">
                    <span>Copyright &copy; Ho Chi Minh City University of Technology 2020</span>
                </div>
                </div>
            </footer>

        </div>
    </div>

    <!-- <script src="./js/script.js"></script> -->
    <script>
        $(function () {
            /* BOOTSTRAP SLIDER */
            $('.slider').bootstrapSlider()

            /* ION SLIDER */
            $('#range_1').ionRangeSlider({
            min     : 0,
            max     : 5000,
            from    : 1000,
            to      : 4000,
            type    : 'double',
            step    : 1,
            prefix  : '$',
            prettify: false,
            hasGrid : true
            })
            $('#range_2').ionRangeSlider()

            $('#range_5').ionRangeSlider({
            min     : 0,
            max     : 10,
            type    : 'single',
            step    : 0.1,
            postfix : ' mm',
            prettify: false,
            hasGrid : true
            })
            $('#range_6').ionRangeSlider({
            min     : -50,
            max     : 50,
            from    : 0,
            type    : 'single',
            step    : 1,
            postfix : '°',
            prettify: false,
            hasGrid : true
            })

            $('#range_4').ionRangeSlider({
            type      : 'single',
            step      : 100,
            postfix   : ' light years',
            from      : 55000,
            hideMinMax: true,
            hideFromTo: false
            })
            $('#range_3').ionRangeSlider({
            type    : 'double',
            postfix : ' miles',
            step    : 10000,
            from    : 25000000,
            to      : 35000000,
            onChange: function (obj) {
                var t = ''
                for (var prop in obj) {
                t += prop + ': ' + obj[prop] + '\r\n'
                }
                $('#result').html(t)
            },
            onLoad  : function (obj) {
                //
            }
            })
        })
    </script>
    <script src="./js/ion.rangeSlider.min.js"></script>
    <script src="./js/bootstrap-slider.min.js"></script>
</body>
</html>