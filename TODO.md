# TODO: Update Export File Name Format

- [x] Modify filename generation in ExportController.php to use url_title + ' (' + locale(s) + ').pdf'
  - For single locale: url_title + ' (' + locale + ').pdf'
  - For 'all' locale: url_title + ' (ka,us).pdf'
  - Use $articles->first()->url_title as base for filename
