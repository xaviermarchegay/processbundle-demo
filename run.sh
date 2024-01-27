#!/bin/bash

./bin/console csv:create
./vendor/bin/phpbench run tests/Benchmark --iterations=3 --report=benchmark_compare
