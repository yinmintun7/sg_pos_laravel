var app = angular.module("myApp", []);
app.controller("myCtrl", function ($scope, $http) {
    $scope.orderList = [];

    $scope.init = function () {
        $scope.orderlist();
      };
$scope.orderlist = function () {
    var data = {
      shift_id: shift_id,
    };

    $http({
      method: "POST",
      url: base_url + "api/get_order_list",
      data: data,
    }).then(
      function (response) {
        if (response.status == 200) {
          $scope.orderList = response.data;
        } else {
          console.log("Error:" + response.status);
        }
      },
      function (error) {
        console.error(error);
      }
    );
  };
  // for order list end/////////////////////////////////////////////////////////////////

  // for cancel order start/////////////////////////////////////////////////////////////////
  $scope.OrderStatus = function (order,status) {

    var data = {
      corderId: order.id,
      status:status,
    };

    $http({
      method: "POST",
      url: base_url + "api/cancel_order",
      data: data,
    }).then(
      function (response) {
        if (response.status == 200) {
          order.canceled =true;
          $scope.init();
          console.log("cancel");
        } else {
          console.log("fail");
        }
      },
      function (error) {
        console.error(error);
      }
    );
  };

  // for cancel order end/////////////////////////////////////////////////////////////////

   // for order detail end/////////////////////////////////////////////////////////////////
   $scope.orderDetailPage=function(id){
    window.location.href = base_url + `sg_frontend/order_detail?id=${id}`;
   }

   $scope.orderEditPage=function(id){
    window.location.href = base_url + `sg_frontend/order_edit.php?id=${id}`;
   }

   $scope.orderDetail = function (orderId) {

    // var data = {
    //   orderId:orderId,
    // };
    $http({
      method: "POST",
      url: base_url + "api/get_order_detail",
      data: {orderId:orderId},
    }).then(
      function (response) {
        if (response.status == 200) {
          $scope.orderDetail =[]
          $scope.orderDetail = response.data;
          $scope.qty = 0;
          response.data.forEach(items => {
            $scope.qty += items.quantity
          });

          // window.location.href = base_url + `sg_frontend/order_detail?id=${orderId}`;
          console.log($scope.orderDetail);
        } else {
          console.log("Error:" + response.status);
        }
      },
      function (error) {
        console.error(error);
      }
    );
  };

  $scope.paymentPage=function(id){
    window.location.href = base_url + `payment/${id}`;
   }

});
