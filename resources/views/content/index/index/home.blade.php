@extends('layouts.index')

@section('content')

    <div class="container">

        <div class="row home-title">
            <div class="col-md-8">

                <h1>Budget Easier, Spend Mindfully</h1>

                <p>
                    Create budgets, log transactions and understand how<br>
                    you're spending your money. No bots, no AI, no automation.
                </p>

            </div>
            <div class="col-md-4 text-center">

                <a href="{{ url('signup') }}" class="btn btn-lg btn-warning mt-5">Get Started Today <i class="fa fa-angle-double-right"></i></a>

            </div>
        </div>

        <div class="screenshot-wrapper text-center">
            <img src="{{ url('images/screenshot.png') }}" alt="">
        </div>

        <div class="steps-wrapper text-center">

            <h2>Start budgeting in 5 steps</h2>

            <img src="{{ url('images/step-1.jpg') }}">

            <div class="number">1</div>

            <h3>Create income and expense categories<br>to start building your budget</h3>


            <img src="{{ url('images/step-2.jpg') }}">

            <div class="number">2</div>

            <h3>Log each transaction, you're<br>responsible for every entry</h3>

            <img src="{{ url('images/step-3.jpg') }}">

            <div class="number">3</div>

            <h3>Establish a budget, cut out frivolous spend<br>and put every dollar to work for you</h3>

            <img src="{{ url('images/step-4.jpg') }}">

            <div class="number">4</div>

            <h3>Tag and track particular deposits and incomes<br>to better understand your particular cashflows</h3>

            <img src="{{ url('images/step-5.jpg') }}">

            <div class="number">5</div>

            <h3>Break bad spending habits and become<br>aware of the cost of your lifestyle</h3>


            <h2>One low price, forever</h2>

            <div class="row">
                <div class="col-md-5 offset-md-1 text-left font-20">

                    <p class="mt-7">
                        We're interested in providing an easy to use, simple budgeting platform with lots of flexibility, not upselling you with complicated options.
                    </p>
                    <p>
                        BudgetPad is offered for one simple price and everyone gets the same unlimited access.
                    </p>
                    <p>
                        We will never increase the price of your subscription for the life of your membership, as long as you don't cancel, you will continue to pay the exact price you signed up for.
                    </p>

                </div>
                <div class="col-md-5">

                    <div class="pricing-wrapper">
                        <div class="title">All-Access</div>
                        <div class="price">{{ Format::currency($amount) }}</div>
                        <em>Billed monthly. You won't be charged until your 14-day Free Trials ends on {{ date('F jS', strtotime('+14 days')) }}</em>
                        <div class="feature">Unlimited Categories</div>
                        <div class="feature">Track Incomes & Expenses</div>
                        <div class="feature">Track Cash Flows with Tags</div>
                        <div class="feature">Advanced Reporting</div>
                        <div class="feature">Safe & Secure</div>
                    </div>

                </div>
            </div>


        </div>

    </div>

@endsection