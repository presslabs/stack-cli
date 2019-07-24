Feature: Test that WP-CLI loads.

  Scenario: WP-CLI loads for your tests
    Given a WP install
    And a input file:
      """
      quay.io/presslabs/test
      test.local
      test.prod
      default
      """

    When I run `cat input | wp stack init`
    Then STDOUT should contain:
      """
      Success: 🙌 Stack initialized successfuly!
      """
    And the .dockerignore file should exist
    And the .dockerignore file should contain:
      """
      .git
      *.md
      skaffold.yaml
      .env*
      """
    And the Dockerfile file should exist
    And the Dockerfile file should contain:
      """
      FROM quay.io/presslabs/wordpress-runtime:5.2-7.3.4-latest as builder
      RUN rm -rf /var/www/html
      COPY --chown=www-data:www-data . /var/www
      WORKDIR /var/www
      RUN composer install -n --no-ansi --no-dev --prefer-dist
      RUN rm -rf .composer
      FROM quay.io/presslabs/wordpress-runtime:5.2-7.3.4-latest
      ENV DOCUMENT_ROOT=/var/www/
      RUN rm -rf /var/www/html
      COPY --from=builder /var/www /var/www

      """
