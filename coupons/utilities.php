<?php


function alertAndBack($msg=""){
  echo "<script>
    alert('$msg');
    window.history.back();
  </script>";
}

function alertGoBack($msg=""){
  echo "<script>
    alert('$msg');
    window.location = './couponsList.php';
  </script>";
}

function confirmGoBack($msg=""){
  echo "<script>
    window.location = './couponsList.php';
  </script>";
}


// 有預設值的參數要往最後放
function timeoutGoBack($time=1000){
  echo "<script>
    setTimeout(()=>window.location = './couponsList.php, $time);
  </script>";
}

// 輸入資料 驗證錯誤用 返回上一頁
function goBack(){
  echo "<script>window.history.back();</script>";
  exit;
}




?>