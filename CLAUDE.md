## DB Access

Query the Joomla database:

curl "https://www.barlowswoodyard.co.uk/staging/db-query.php" \
  -H "X-Query-Token: K7mP2xQnR5vW8jH4dL9fB3cY6tA1sN0e" \
  -H "Content-Type: application/json" \
  -d '{"sql": "SELECT id, title FROM #__content LIMIT 10"}'

Table prefix shorthand #__ expands to jos_ (verify against configuration.php on the server — update this line if different)
SELECT queries only.
Returns JSON: { success, row_count, rows }

## FTP Deployment

Write a script file (e.g. deploy.txt) with:

open ftps://claude%40barlowswoodyard.co.uk:N6%26VbHUAV%2C_%7EbNXI@barlowswoodyard.co.uk -explicit -hostkey=*
synchronize remote ./ /public_html/staging/ -delete -filemask="|.git/;.github/;CLAUDE.md;db-query.php;node_modules/"
exit

Then run:
& "$env:LOCALAPPDATA\Programs\WinSCP\WinSCP.com" /script="deploy.txt"

Remote root: /public_html/staging/
Site URL: https://www.barlowswoodyard.co.uk/staging/
Joomla table prefix: jos_ (verify — see DB Access note above)
