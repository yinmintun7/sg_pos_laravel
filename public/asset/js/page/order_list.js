var app = angular.module("myApp", []);
app.controller("myCtrl", function ($scope, $http) {
    $scope.orderList = [];
    $scope.shift_id = shiftId;
    $scope.init = function () {
        $scope.orderlist();
      };

$scope.orderlist = function () {
    var data = {
      shift_id: shiftId,
    };
    $http({
      method: "POST",
      url: base_url + "get-order-list",
      data: data,
    }).then(
      function (response) {
        if (response.status == 200) {
          $scope.orderList = response.data.data;
          console.log($scope.orderList);
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
      url: base_url + "cancel-order",
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
    ).catch(function (error) {
        if (error.status === 422) {
            var errors = error.data.errors;
            angular.forEach(errors, function (error) {
                $scope.showError(error);
            });
        } else {
            console.error("An error occurred:", error);
        }
    });
  };

  // for cancel order end/////////////////////////////////////////////////////////////////

   // for order detail end/////////////////////////////////////////////////////////////////
   $scope.orderDetailPage=function(id){
    window.location.href = base_url + `order-detail-page/${id}`;
   }

   $scope.orderEditPage=function(id){
    window.location.href = base_url + `order-edit/${id}`;
   }

   $scope.orderDetail = function (orderId) {

    var data = {
      orderId:orderId,
    };
    $http({
      method: "POST",
      url: base_url + "api/get_order_detail",
      data:data,
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

   $scope.showError = function(error) {
    new PNotify({
        title: 'Error!',
        text: error,
        type: 'error',
        styling: 'bootstrap3'
    });
}

});
