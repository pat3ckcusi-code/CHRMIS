# Leave API — server-side validations and date cancellation

## Endpoints added

1. **POST** /api/leave/apply.php
   - Params: `leave_type`, `date_from` (YYYY-MM-DD), `date_to` (YYYY-MM-DD), `reason` (optional), `document` (file optional)
   - Validations (server-side):
     - `date_from` and `date_to` must be valid dates
     - `date_from` must not be in the past
     - `date_to` must be >= `date_from`
     - All dates in the range must be weekdays (weekends rejected)
     - If applying for VL or SL, the requested days are checked against available credits and rejected if insufficient
   - Response: JSON `{ success: true, leave_id: <id>, total_days: <int> }` or `{ error: '...' }`

2. **POST** /api/leave/cancel_date.php
   - Params: `leave_id`, `date` (YYYY-MM-DD), `reason` (optional)
   - Behavior:
     - Marks the given date in `leave_dates` as cancelled
     - If the linked `filedleave` is already approved, the employee's credits are refunded (+1 day) and the leave's `TotalDays`/`NumDays` are decremented
   - Response: JSON `{ success: true }` or `{ error: '...' }`

## Notes & Caveats
- The implementation creates a `leave_dates` table if it does not exist, and stores per-day rows for weekdays only.
- Holidays are not automatically excluded because no central `holidays` table was found. If you have or want a holiday table, we can extend validations to exclude holiday dates when counting `totalDays`.
- The credit refund assumes a whole-day refund (+1). If you support partial-day (0.5) logic, we should update the calculation and stored values accordingly.
- UI remains unchanged; client-side weekend/min-date protections are in the leave modal. Server-side is authoritative.

## Manual test checklist
1. Open the Apply Leave modal and submit a weekday range; confirm response success and a new `filedleave` record and `leave_dates` rows for each weekday.
2. Try to submit a range that includes a Saturday or Sunday — API should return 400 with a `bad_date` in response.
3. Try to submit a `date_from` in the past — API should return 400.
4. Approve a leave (use existing approval flow). Confirm leavecredits are reduced as before.
5. Call `POST /api/leave/cancel_date.php` with `leave_id` and a date covered by the approved leave — confirm `leave_dates.IsCancelled = 1`, leavecredits were incremented by 1 for that employee, and the `filedleave.NumDays` (and `TotalDays` when present) decreased by 1.

If you want, I can now:
- Add holiday table support and update the apply API to deduct holiday dates from `totalDays` automatically.
- Add UI hooks to cancel a single date from the employee/HR leave pages.
