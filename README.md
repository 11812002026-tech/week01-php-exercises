# Week 01 PHP Exercises - Full Stack Web Development

Fictional data only. Copy this folder into the XAMPP `htdocs` directory and open
`http://localhost/week01-php-exercises/exerciseNN/` (NN = 01 to 10).

| # | Topic | Key idea |
|---|-------|----------|
| 01 | Citizen profile | associative array, `displayProfileField()`, CID length warning |
| 02 | Age and eligibility | `classifyCitizen()` returns group, eligible and explanation |
| 03 | Household summary | loops for shortest/longest name, `summariseMembers()` |
| 04 | Dzongkhag counter | dynamic counters in one associative array |
| 05 | Record search | case-insensitive `searchProfiles()` with `stripos()` |
| 06 | Validation engine | `validateProfile()` returns `isValid` and `errors` |
| 07 | Fee calculator | `calculateServiceFee()` returns every calculation step |
| 08 | Permission checker | permissions stored in an associative array |
| 09 | Records report | separate counting functions, HTML tables, interpretation |
| 10 | Dashboard | stats, filters, warnings, permission check, data-quality score |

Exercise 10 selections can be changed in the URL:
`.../exercise10/?dzongkhag=Paro&status=Active&role=viewer&action=delete`

## Suggested Git workflow
```
git init
git add exercise01 && git commit -m "Add fictional profile display exercise"
git add exercise02 && git commit -m "Implement age classification function"
git add exercise03 && git commit -m "Add household member summary"
git add exercise04 && git commit -m "Add dzongkhag service counter"
git add exercise05 && git commit -m "Add case-insensitive profile search"
git add exercise06 && git commit -m "Add profile validation rules"
git add exercise07 && git commit -m "Add service fee calculator"
git add exercise08 && git commit -m "Complete role permission checker"
git add exercise09 && git commit -m "Add B-PIS records report"
git add exercise10 README.md && git commit -m "Finish B-PIS dashboard and data-quality report"
```

## Review question answers
73. **Associative arrays and B-PIS records:** each field is reached by a meaningful key (`$p['dzongkhag']`) rather than a position, so code is self-documenting and survives field reordering.
74. **Loops vs repeated statements:** when the same step applies to many items or an unknown number of items. A loop is shorter, has one place to fix bugs, and handles 6 or 6,000 records alike.
75. **Functions and maintainability:** a rule (such as age classification) is written once, named, tested in isolation and reused; changing it later means editing one place.
76. **Strict comparison:** `===` checks type as well as value, so `"0"`, `0`, `false` and `""` are not confused. It prevents surprises such as `"abc" == 0` behaviour in older PHP, and invalid data such as the age `"30"` is not silently accepted.
77. **Validation and data quality:** it stops missing, malformed or out-of-range data at the door and reports every problem, so stored records stay complete and trustworthy for reports.
78. **Careful browser output:** unescaped data can break the HTML layout or inject scripts (XSS). `htmlspecialchars()` plus tidy structure keeps output safe and readable.
79. **Git commits and progress:** each commit with a clear message is a snapshot of one finished step, so the history shows the work growing in stages and lets you compare or roll back.
