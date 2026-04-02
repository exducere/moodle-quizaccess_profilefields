# Quiz access rule: Profile field conditions #

This plugin adds an access rule to the Moodle quiz module that restricts quiz
access based on custom user profile field values. Administrators define reusable
conditions globally, and teachers assign those conditions to individual quizzes.
When a student attempts to open the quiz, the plugin evaluates the conditions
against that student's profile and either grants or blocks access, displaying a
configurable message when access is denied.

## Features ##

- Define **reusable conditions** at the site level (no per-quiz duplication).
- Support for **7 operators**: contains, does not contain, equals, contains
  (case-insensitive), does not contain (case-insensitive), is empty, is not empty.
- Configurable behavior when the **profile field has no data**: allow or deny
  access.
- **Custom block message** per condition, shown with contextual details
  (username, quiz name, course, triggered condition, current value, and date).
- Plugin can be **enabled or disabled globally** from Site Administration.
- **Default conditions** can be pre-selected for all new quizzes.
- Multiple conditions can be assigned to a single quiz; all must be satisfied
  for access to be granted.

## Requirements ##

- Moodle 4.2 or later.
- At least one custom user profile field must exist
  (_Site administration > Users > User profile fields_) before creating
  conditions.

---

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can also be installed by putting the contents of this directory to:

    {your/moodle/dirroot}/mod/quiz/accessrule/profilefields

Afterwards, log in to your Moodle site as an admin and go to _Site
administration > Notifications_ to complete the installation.

Alternatively, you can run:

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

---

## Configuration ##

### Step 1 — Enable the plugin ###

Go to _Site administration > Plugins > Activity modules > Quiz > Profile field
conditions_ and make sure **Enable profile condition** is checked.

### Step 2 — Create conditions ###

In the same settings page, click **Manage profile conditions > Add condition**
and fill in the form:

| Field | Description |
|---|---|
| **Name** | A descriptive label for the condition (e.g. "Department equals HR"). |
| **Profile field** | The custom user profile field to evaluate. |
| **Operator** | How to compare the field value (see table below). |
| **Value** | The value to compare against (not required for *is empty* / *is not empty*). |
| **When profile field is missing** | What to do when the user has no data for this field: **Exclude** (deny access) or **Include** (allow access). |
| **Custom message** | HTML message displayed to the user when this condition blocks access. Leave empty to use the default message. |

**Available operators:**

| Operator | Description |
|---|---|
| `Contains` | Field value contains the given string (case-sensitive). |
| `Does not contain` | Field value does NOT contain the given string (case-sensitive). |
| `Equals` | Field value is exactly the given string. |
| `Contains (case insensitive)` | Same as *Contains* but ignores case. |
| `Does not contain (case insensitive)` | Same as *Does not contain* but ignores case. |
| `Is empty` | Field value is blank or not set. |
| `Is not empty` | Field value has any non-blank content. |

### Step 3 — Assign conditions to a quiz ###

Open a quiz in edit mode (_Settings > Extra restrictions on attempts_). Under
**Profile field conditions**, check one or more of the conditions you created.
Save the quiz. The rule is now active for that quiz.

---

## Practical example ##

**Scenario:** Your institution uses a custom profile field called **Department**
(shortname: `department`). You want to restrict a quiz titled "HR Compliance
Test" so that only users from the Human Resources department can access it.
Everyone else should see a clear explanation.

### 1. Create the profile field (if not yet done) ###

_Site administration > Users > User profile fields > Create a new profile
field_ — choose **Text input**, name it `Department`, shortname `department`.

### 2. Create the condition ###

_Site administration > Plugins > Activity modules > Quiz > Profile field
conditions > Manage profile conditions > Add condition_

```
Name:                 "Allowed: HR department only"
Profile field:        Department (department)
Operator:             Equals
Value:                HR
When field is missing: Exclude user (deny access)
Custom message:       "Access to this quiz is restricted to HR staff.
                       If you believe this is an error, please contact
                       your administrator."
```

### 3. Assign to the quiz ###

Open **HR Compliance Test > Settings > Extra restrictions on attempts**.  
Under **Profile field conditions**, check **Allowed: HR department only** and
save.

### 4. Student experience ###

| Student profile `department` value | Result |
|---|---|
| `HR` | Access granted. |
| `Finance` | Access blocked — custom message is shown with the student's name, quiz, course, triggered condition name, actual field value, and date. |
| *(field has no value)* | Access blocked (field is missing → Exclude). |

---

## Privacy ##

This plugin does not store any personal data. It reads existing Moodle user
profile data only at the moment of access evaluation and does not persist or
transmit that data elsewhere.

## License ##

2025 Exducere Online <https://exducereonline.com>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE. See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program. If not, see <https://www.gnu.org/licenses/>.
