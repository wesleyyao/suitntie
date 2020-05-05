<div class="modal fade" id="testResultModal" tabindex="-1" role="dialog" aria-labelledby="testResultTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testResultTitle">测试结果</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="testResultBody">
                <div id="testResultMessage"></div>
                <div class="row">
                    <div class="col-12">
                        <div style="min-height: 200px;">
                            <div class="container text-center result-title-cell" style="z-index: 2">
                                <h1 class="display-4" id="resultTitle"></h1>
                                <h2 id="resultDescription"></h2>
                                <div class="lead" id="resultTags"></div>
                            </div>
                            <img id="characterImg" src="" />
                        </div>
                    </div>
                </div>
                <br/><br/>
                <div class="row">
                    <div class="col-12">
                        <div id="resultCharts">
                            <div class="row">
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <canvas id="myChart0" height="300"></canvas>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <canvas id="myChart1" height="300"></canvas>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <canvas id="myChart2" height="300"></canvas>
                                </div>
                                <div class="col-lg-3 col-md-6 col-sm-12">
                                    <canvas id="myChart3" height="300"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-12">
                        <h3>维度解释</h3>
                        <div id="dimensionAnalytics">
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-12">
                        <h3>基本分析</h3>
                        <div id="basicAnalytics">
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-12">
                        <h3>你的优势</h3>
                        <div id="advantageList">
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                     <div class="col-12">
                        <h3>你的盲点</h3>
                        <div id="disadvantageList">
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class="col-12">
                        <h3>专业和职业分析</h3>
                        <div id="programAndJobAnalytics">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>