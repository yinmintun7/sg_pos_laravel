var app = angular.module("myApp", []);
app.controller("myCtrl", function ($scope, $http) {
  $scope.orderDetal=[];

  $scope.init = function () {
  
  };
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


});
