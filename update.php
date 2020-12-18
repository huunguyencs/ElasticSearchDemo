<?php
    use Elasticsearch\ClientBuilder;

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


    /**
     * Document Structer
     * - name
     * - price
     * - tag
     */

    $id = $_POST['id'] ?? null;
    $name = $_POST['name'] ?? null;
    $price = $_POST['price'] ?? null;
    $tags = $_POST['tags'] ?? null;

    $msg = "";
    $attr = "";

    if ($id != null && $name != null && $price != null && $tags!=null){

        $params = [
            'index' => 'product',
            'type' => 'product_type',
            'id' => $id,

            'body' => [
                'name' => $name,
                'price' => $price,
                'tags' => explode(",",$tags)
            ]
        ];

        try {
            $client->index($params);
            $msg = "Update successful. Id = " . $id;
            $attr = "card-body text-success";
            $id = $name = $price = $tags = null;
        } catch (\Throwable $th) {
            $msg = "Update fail.";
            $attr = "card-body text-danger";
        }
        
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>
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

            <li class="nav-item active">
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
            <li class="nav-item">
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

                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" action="search.php" method="post">
                        <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2" name="search" id="search">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fa fa-search" aria-hidden="true"></i>
                            </button>
                        </div>
                        </div>
                    </form>

                </nav>


                <div class="container-fluid">
                    <section class="content">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-md-12">
                                    <!-- jquery validation -->
                                    <div class="card card-primary">
                                        <div class="card-header">
                                            <h3 class="card-title">Update Document</h3>
                                        </div>
                                        <form role="form" id="quickForm" action="#" method="post">
                                            <div class="card-body">
                                                <div class="form-group">
                                                    <label for="id">Id</label>
                                                    <input type="text" name="id" class="form-control" id="id" placeholder="Enter id" value="<?=$id?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="name">Name</label>
                                                    <input type="text" name="name" class="form-control" id="name" placeholder="Enter name" value="<?=$name?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="price">Price</label>
                                                    <input type="text" name="price" class="form-control" id="price" placeholder="Enter price" value="<?=$price?>">
                                                </div>
                                                <div class="form-group">
                                                    <label for="tags">Tags <i>(seperate by comma)</i></label>
                                                    <input type="text" name="tags" class="form-control" id="tags" placeholder="Enter tags" value="<?=$tags?>">
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <button type="submit" class="btn btn-primary">Update</button>
                                            </div>
                                            <div class ="<?=$attr?>"><i><?=$msg?></i></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
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