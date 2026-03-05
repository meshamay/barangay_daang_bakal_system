# Excel Charts Feature - Reports & Analytics

## Summary
Ang Excel export sa **Admin > Reports & Analytics** ay nag-export na ng tunay na `.xlsx` file kasama ang mga chart na automatically generated base sa selected sections.

---

## Changes Made

### 1. Dependencies
- Nag-install ng `phpoffice/phpspreadsheet` v2.2+ para makapag-generate ng XLSX files with charts
- **Note:** Sa production server, kailangan naka-enable ang PHP extensions:
  - `ext-gd` (for image support)
  - `ext-zip` (for .xlsx compression)

### 2. Controller Updates
- **File:** `app/Http/Controllers/Admin/ReportsController.php`
- Updated `export()` method para mag-route ng Excel request to new XLSX generator
- Replaced CSV generator (`generateExcelCsv`) with full chart-enabled XLSX generator (`generateExcelXlsx`)

### 3. Excel Output Features
Ang generated `.xlsx` file ay may:
- **Two sheets:**
  1. **Report Data**: Lahat ng tables with KPI cards at data per selected section
  2. **Charts**: Visual charts automatically generated para sa bawat selected section

- **Chart Types:**
  - `Population by Gender` → Pie Chart
  - `Total Requests & Complaints` → Column Chart
  - `Most Requested Document` → Bar Chart
  - `Request Status Summary` → Column Chart
  - `Most Reported Complaints` → Bar Chart
  - `Complaints Status Summary` → Column Chart

### 4. User Workflow (Walang bago sa frontend!)
1. Pumunta Admin sa **Reports & Analytics** page
2. I-click ang **Export** button
3. Piliin ang **Excel Spreadsheet** at i-select ang sections
4. I-download ang `.xlsx` file kasama na ang mga chart

---

## Technical Details

### Helper Methods
- `writeSectionTable()` - Writes data table to worksheet para sa bawat section
- `addChart()` - Creates and positions chart object sa Charts sheet using PhpOffice Spreadsheet Chart API

### Chart Configuration
- Charts dynamically positioned sa Charts sheet (18 rows spacing)
- Data range references point to Report Data sheet cells
- Auto-sized columns para sa better readability

---

## Testing
✅ No syntax errors  
✅ All unit tests passing  
✅ Existing report features intact (PDF export, filtering, etc.)  
✅ Excel export generates `.xlsx` with embedded charts

---

## Next Steps / Notes
- Kung may error sa production regarding `ext-gd` or `ext-zip`, contact server admin para i-enable ang extensions sa PHP configuration
- Pwede pang i-customize ang chart colors, legend positions, at layouts sa `addChart()` method kung gusto ng additional tweaks

---

**Implementation Date:** March 5, 2026  
**Developer:** AI Assistant (GitHub Copilot)
