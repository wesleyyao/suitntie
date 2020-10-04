<div class="modal fade" id="testResultModal" tabindex="-1" role="dialog" aria-labelledby="testResultTitle"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="testResultTitle">测试结果</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="testResultBody">
                <button class="btn btn-primary btn-lg float-btn" id="screenshotBtn"><i class="fas fa-camera"></i>
                    截屏</button>
                <div id="captureBody">
                    <div id="mainResultDiv" class="result-div">
                        <div class="resultHeader">
                            <div class="text-center result-title-cell" style="z-index: 2; width: 100%">
                                <h1 class="display-4" id="resultTitle"></h1>
                                <h2 id="resultDescription"></h2>
                            </div>
                        </div>
                        <div class="resultDimension">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none">
                                <polygon stroke="white" fill="white" points="0,100 100,0 100,100" />
                            </svg>
                            <div class="row dimensionContent">
                                <div class="col-lg-4 col-md-4 col-sm-12 text-center">
                                    <img id="characterImg" src="" class="img-fluid" />
                                </div>
                                <div class="col-lg-8 col-md-8 col-sm-12">
                                    <div id="resultCharts" class="resultBarChart lightShadow">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <canvas id="myChart0" height="300"></canvas>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <canvas id="myChart1" height="300"></canvas>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <canvas id="myChart2" height="300"></canvas>
                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <canvas id="myChart3" height="300"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 tagBox">
                                        <div class="lead" id="resultTags"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row dimension-analysis">
                        <div class="col-12">
                            <h3>维度解释</h3>
                            <div id="dimensionAnalytics">

                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row basic-analysis">
                        <div class="col-12 ">
                            <h3>基本分析</h3>
                            <div id="basicAnalytics">

                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row advantages">
                        <div class="col-12 ">
                            <h3>你的优势</h3>
                            <div id="advantageList">

                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row disadvantages">
                        <div class="col-12 ">
                            <h3>你的盲点</h3>
                            <div id="disadvantageList">

                            </div>
                        </div>
                    </div>
                    <br />
                    <div class="row careerDesc">
                        <div class="col-12">
                            <h3>专业和职业分析</h3>
                            <div id="programAndJobAnalytics">

                            </div>
                        </div>
                    </div>
                    <br />
                </div>
                <div id="testBody"></div>
                <a id="downloadLink">Download it</a>
                <img src="" id="imgg" />
            </div>
        </div>
    </div>
</div>