<?php 
require_once 'inc/session.php';
if (!is_manager()) {
    echo "<script>alert('관리자만 접근 가능합니다.'); location.href='index.php';</script>";
    exit;
}
require_once("contents.import.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>관리자 페이지-상품 관리</title>
</head>

<body id="manager_body">
    <main class="manager_wrapper product">
        <div class="main_menu_wrapper">
            <a href="index.php">
                <div class="menu" style="background-color: #444; color: white;"> 메인으로 </div>
            </a>

            <a href="manager_home.php">
                <div class="menu"> 홈 </div>
            </a>
            <a href="manager_member.php">
                <div class="menu"> 회원 관리 </div>
            </a>
            <a href="manager_notice.php">
                <div class="menu" > 공지사항 관리 </div>
            </a>
            <a href="manager_product.php">
                <div class="menu" style="background-color:  rgb(74 173 255);"> 상품 관리 </div>
            </a>
            <a href="manager_event.php">
                <div class="menu"> 이벤트 관리 </div>
            </a>
            <a href="manager_inquiry.php">
                <div class="menu"> 고객 문의 관리 </div>
            </a>
        </div>


        <div class="main_display">
            <header>
                <div class="login_info">
                    <span class="on_id"> 접속 아이디: avbs345 </span>
                    <span class="on_dep"> 부서: 웹팀 </span>
                </div>
                <a href="index.php"><button class="logout"> logout </button></a>
            </header>
            <section class="contents">

                <section class="contents_header">
                    <div class="title_and_order">
                        <span class="title">상품 관리</span>
                        <div class="order_wrapper">
                            <div class="order_one">
                                <div class="registration"> 등록순 </div>
                                <div class="check"> 조회순 </div>
                            </div>
                            <div class="order_two">
                                <div class="ascending_order"> 오름차순 </div>
                                <div class="descending_order"> 내림차순 </div>
                            </div>
                        </div>
                    </div>
                    <input type="text" class="search_product">
                    <a href="product_registration.php"><button class="regist_product"> 등록 </button></a>
                    <a href=""><button class="delete_product"> 삭제 </button></a>
                </section>
                <article class="scroller">
                    <form action="" method="PRODUCT">
                        <section class="board product">
                            <div class="table_header">
                                <div class="table_col class">카테고리</div>
                                <div class="table_col title">상품명</div>
                                <div class="table_col code">상품코드</div>
                                <div class="table_col deliv">오늘배송</div>
                                <div class="table_col date">날짜</div>
                            </div>
                        <?php foreach($result as $r){?>
                            <?php 
                                //content_code를 이용해 날짜 추출
                                $content_code= $r['content_code'];
                                $year = "20".substr($content_code,0,2);
                                $month = substr($content_code,2,2);
                                $day = substr($content_code,4,2);
                                $date = $year.'-'.$month.'-'.$day;
                                
                                ?>
                            <div class="table_row">
                                <div class="table_col class"></div>
                                <div class="table_col title"><?php echo "$r[content_name]"?></div>
                                <div class="table_col code"><?php echo "$r[content_code]"?></div>
                                <div class="table_col deliv"><?php echo "$r[deliv_today]"?></div>
                                <div class="table_col date"><?php print_r($date);?></div>
                            </div>
                        <?php
                        }
                        ?>
                        </section>
                    </form>  
                </article>
            </section>
        </div>
    </main>
</body>

</html>