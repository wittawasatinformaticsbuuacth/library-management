---
name: Test Case
about: Template for creating test cases
title: "[TEST] TC-XXX: "
labels: ["test-case", "status-not-run"]
assignees: ""
---

## Test Case Information

**Test Case ID:** TC-XXX  
**Module:** Member Management > Registration  
**Priority:** High / Medium / Low  
**Type:** Positive / Negative

## Description

[Brief description of what this test case verifies]

## Preconditions

- [ ] Precondition 1
- [ ] Precondition 2

## Test Data

```json
{
  "member_code": "MEMXXXX",
  "full_name": "Test Name",
  "email": "test@example.com",
  "phone": "081XXXXXXXX",
  "member_type": "student"
}
```

## Test Steps

| Step | Action | Expected Result |
| ---- | ------ | --------------- |
| 1    | ...    | ...             |
| 2    | ...    | ...             |

## Expected Results

- [ ] Result 1
- [ ] Result 2

## Actual Results

[To be filled during execution]

## Status

- [ ] Not Run
- [ ] Pass
- [ ] Fail
- [ ] Blocked

## Notes

[Any observations or defect references]

**Traceability:** FR-XXX, TC-YYY, #bug_id
