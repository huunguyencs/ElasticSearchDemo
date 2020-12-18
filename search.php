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

    $search = $_POST['search'] ?? null;

    if ($search != null) {
        $params = [
            'index' => 'product',
            'type' => 'product_type',
            'body' => [
                'query' => [
                    'bool' => [
                        'should'=> [
                            ['match' =>['name' => $search]],
                            ['match' => ['tags' => $search]]
                        ]
                    ]
                ]
            ]
        ];
        $items = null;
        $total = 0;
        try {
            $results = $client->search($params);
            $total = $results['hits']['total']['value'];
        } catch (\Throwable $th) {
            $results = null;
        }
        if ($total > 0) {
            $items = $results['hits']['hits'];
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

                <div class="container-fluid">
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
                                                    <label for="search">Search</label>
                                                    <input type="text" name="search" class="form-control" id="search" placeholder="Search for ..." value="<?=$search?>">
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary">Search</button>
                                            </div>
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

    
</body>
</html>