<?php

function alertAndBack($msg=""){
  echo "<script>
    alert('$msg');
    window.history.back();
  </script>";
}

// function alertAndBack($msg = "發生錯誤") {
//     echo '
//     <!DOCTYPE html>
//     <html lang="zh-Hant">
//     <head>
//       <meta charset="UTF-8">
//       <title>提示訊息</title>
//       <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
//       <meta http-equiv="refresh" content="2;url=javascript:history.back()">
//     </head>
//     <body>
//       <div class="container mt-5">
//         <div class="alert alert-warning text-center" role="alert">
//           '.htmlspecialchars($msg, ENT_QUOTES).'<br>
//           <small>即將返回上一頁...</small>
//         </div>
//       </div>
//     </body>
//     </html>';
//     exit;
//   }

function alertGoBack($msg=""){
  echo "<script>
    alert('$msg');
    window.location = './productlist.php';
  </script>";
  exit;
}

// 有預設值的參數要往最後放
function timeoutGoBack($time=1000){
  echo "<script>
    setTimeout(()=>window.location = './productlist.php', $time);
  </script>";
}

function goBack(){
  echo " <br><button style='width:120px'  onclick='goBack()'>回上一頁</button>";
  echo "<script>
          function goBack(){
            window.history.back();
          }
        </script>";
}

function alertGoTo($msg="", $url="./productlist.php"){
    echo "<script>
      alert('$msg');
      window.location = '$url';
    </script>";
  }
?>