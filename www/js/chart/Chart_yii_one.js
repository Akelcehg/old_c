/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */



    for (var key in chartGlobalConfig) {
        if (chartGlobalConfig[key] != null) {
            Chart.defaults.global[key] = chartGlobalConfig[key];


        }
    }





