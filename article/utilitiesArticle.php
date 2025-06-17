<?php

function alertAndBack($msg = "")
{
    echo "<script>
            alert('$msg');
            window.history.back();     
        </script>";
}

function alertGoBack($msg = "")
{

    echo "<script>
        alert('$msg');
        window.location = './articleList.php';
     </script>";
}
function alertGoTo($msg = "", $url = "./articleList.php")
{
    echo "<script>
    alert('$msg');
    window.location = '$url';
  </script>";
}

// 有預設值的參數要往最後放
function timeoutGoBack($time = 1000)
{

    echo "<script>
        setTimeout(()=>window.location = './articleList.php',$time);
     </script>";
}

function goBack()
{
    echo " <button onclick='goBack()'>回上一頁</button>";
    echo "
            <script>
                function goBack() {
                    window.history.back();
                }
            </script>
        ";
}
?>