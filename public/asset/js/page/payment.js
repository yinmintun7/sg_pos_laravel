var app = angular.module("myApp", []);
app.controller("myCtrl", function ($scope, $http) {
  $scope.orderDetail = [];
  $scope.kyats = [];
  $scope.selectIndex = [];
  $scope.selectQuantity = "";
  $scope.balance='';
  $scope.total_cash='';
  $scope.refund='';
  $scope.disable = false;
  $scope.topaydisable = true;
  $scope.hideOrderDetail =true;

  $scope.orderDetail = function (orderId) {
    var data = {
      orderId:orderId,
     shift_id:shiftId
    };
    var url = base_url + "get-order-detail";
    $http({
      method: "POST",
      url:url,
      data:data ,
    }).then(
      function (response) {
        if (response.status == 200) {
          $scope.orderDetail = response.data.data;
          console.log($scope.orderDetail);
          $scope.qty = 0;
          $scope.orderDetail.order_detail.forEach(item => {
              $scope.qty += item.quantity;
          });

          // window.location.href = base_url + `sg_frontend/order_detail?id=${orderId}`;
        } else {
          console.log("Error:" + response.status);
        }
      },
      function (error) {
        console.error(error);
      }
    );
  };
  $scope.payCash = function (value) {
    const kvalue = parseInt(value);
    const index = $scope.kyats.length + 1;
    const quantity = ($scope.selectQuantity =='') ? 1 : $scope.selectQuantity;
    const total_cash = kvalue*quantity;
    const cash = { cash: kvalue, index: index, quantity:quantity,total_cash: total_cash };
    $scope.kyats.push(cash);
    $scope.calculateValue();
    console.log($scope.kyats);
    $scope.clear();
  };
  $scope.selectedCash = function (index) {
    var indexPosition = $scope.selectIndex.indexOf(index);
    if (indexPosition !== -1) {
      $scope.selectIndex.splice(indexPosition, 1);
    } else {
      $scope.selectIndex.push(index);
    }
  };
  $scope.voidSelect = function () {
    $scope.kyats = $scope.kyats.filter(function (kyat) {
      return $scope.selectIndex.indexOf(kyat.index) === -1;
    });
    $scope.selectIndex = [];
    for (let i = 0; i < $scope.kyats.length; i++) {
      const index = i + 1;
      $scope.kyats[i].index = index;
    }
    $scope.calculateValue();
  };

  $scope.clickValue = function (cvalue) {
   $scope.selectQuantity = $scope.selectQuantity + parseInt(cvalue);
  };

  $scope.clear =function(){
    $scope.selectQuantity ='';

  }
  $scope.calculateValue = function(){
    let total_cash_res=0;
    for(i=0;i<$scope.kyats.length;i++){
      const total=$scope.kyats[i].total_cash
      total_cash_res = parseInt(total_cash_res) + parseInt(total);
    }
    if(total_cash_res >= $scope.orderDetail.total_amount){
      $scope.topaydisable =false;
      $scope.refund = parseInt(total_cash_res) - parseInt($scope.orderDetail.total_amount ) ;
    }else{
      $scope.refund =0;
    }
    // $scope.refund = (total_cash_res >= $scope.orderDetail[0].total_amount) ? parseInt(total_cash_res) - parseInt($scope.orderDetail[0].total_amount ) : 0;
    $scope.balance= $scope.orderDetail.total_amount - total_cash_res;
    $scope.balance = ($scope.balance < 0) ? 0 : $scope.balance;

  };

  $scope.payOrder = function () {
    let customer_pay_amount=0;
    for(i=0;i<$scope.kyats.length;i++){
      const total=$scope.kyats[i].total_cash
      customer_pay_amount = parseInt(customer_pay_amount) + parseInt(total);
    }
    var data = {
      id: $scope.orderDetail.id,
      order_no: $scope.orderDetail.order_no,
      refund : $scope.refund,
      customer_pay_amount : customer_pay_amount,
      kyats : $scope.kyats,
    };
    console.log(data);
    $http({
      method: "POST",
      url: base_url + "insert-paid-order",
      data: data,
    }).then(
      function (response) {
        if (response.status == 200) {
          console.log(response.data)
          window.location.href = base_url + "order-list";
        } else {
          console.log("fail");
        }
      },
      function (error) {
        console.error(error);
      }
    );
  };

});
