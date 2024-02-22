var app = angular.module("myApp", []);
app.controller("myCtrl", function ($scope, $http) {
  $scope.orderDetal=[];

  $scope.init = function () {

  };
  $scope.orderDetail = function (orderId) {

    var data = {
      orderId:orderId,
      shift_id:shiftId
    };
    var url = base_url + "get-order-detail";
    $http({
      method: "POST",
      url: url,
      data:data,
    }).then(
      function (response) {
        if (response.status == 200) {
          $scope.orderDetail = [];
          $scope.orderDetail = response.data.
          data;
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


});
