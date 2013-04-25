
def run_tests
  `vendor/bin/phpunit --debug`
end

guard 'shell', :all_on_start => true do
  watch(%r{^(tests|lib)/.*\.php$}) { run_tests }
  watch(%r{^[^/]+\.php$}) { run_tests }
end
