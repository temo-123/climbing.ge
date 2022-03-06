
<template>
    <div class="container purchase overflow-auto">
        <div>
            <div class="row">
                <div class="col-md-6">
                    <button class="btn btn-primary pull-left">Back</button>
                </div>
                <div class="col-md-6">
                    <button class="btn btn-primary pull-right">Next ></button>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-2 col-xs-2">
                    <img src="/public/images/site_img/site_logo/climibng,ge(becground).jpg">
                </div>
                <div class="col-sm-10 col-xs-10 company-details">
                    <div>company address, city, postal</div>
                    <div>company phone +0987654</div>
                    <div>company fax +098765</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-3 col-xs-3 to-details">
                    <div>PURCHASED ORDER FOR :</div>
                    <div class="to-name">Company A</div>
                    <div class="to-address">Company A Address</div>
                    <div class="to-city">City, Postal</div>
                </div>
                <div class="col-sm-9 col-xs-9 purchase-info">
                    <h4 class="info-code">PO/1212/121212</h4>
                    <div class="info-date">Issued : 23-Dec-2018</div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12 table-responsive">
                    <table class="table table-condensed" border="0" cellspacing="0" cellpadding="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center col-xs-1 col-sm-1">#</th>
                                <th class="text-center col-xs-7 col-sm-7">Name</th>
                                <th class="text-center col-xs-1 col-sm-1">Qty</th>
                                <th class="text-center col-xs-3 col-sm-3">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="col-xs-1 col-sm-1 text-center">1</td>
                                <td class="text-center">Item bought number 1</td>
                                <td class="text-center">5 Pcs</td>
                                <td class="text-right">50.000,00</td>
                            </tr>
                            <tr>
                                <td class="text-center">2</td>
                                <td class="text-center">Item bought number 2</td>
                                <td class="text-center">10 Pcs</td>
                                <td class="text-right">100.000,00</td>
                            </tr>
                            
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2">
                                    <!-- Information<br>
                                    Information content -->
                                </th>
                                <th class="text-center">Total</th>
                                <th class="text-right">150.000,00</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    
</script>

<style scoped>
.purchase
{
    position: relative;
    background-color: #FFF;
    min-height: 680px;
    padding: 15px;
    font-family: Times New Roman;
}
.purchase header
{
    padding: 0px 0px 0px 0px;
    margin-bottom: 0px;
    border-bottom: 1px solid #3989c6;
}
.purchase header img
{
    max-width: 200px;
    margin-top: 0;
    margin-bottom: 0;
}
.purchase .company-details
{
    text-align: right;
    margin-top: 0;
    margin-bottom: 0;
}
.purchase main
{
    padding: 0px 0px;
    margin-bottom: 0px;
}
.purchase .to-details
{
    text-align: left;
}
.purchase .to-name
{
    font-weight: bold;
}
.purchase .to-name .to-address .to-city
{
    margin-top: 0;
    margin-bottom: 0;
}
.purchase .purchase-info
{
    text-align: right;
}
.purchase-info .info-code
{
    font-weight: bold;
}
.purchase-info .info-code .info-date
{
    margin-top: 0;
    margin-bottom: 0;
}


</style>