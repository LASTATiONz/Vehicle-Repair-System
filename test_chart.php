<!DOCTYPE html>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', { packages: ['corechart'] });
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        // ตัวแปลงชื่อเดือนจาก eng เป็น th
        function convertMonthToThai(month) {
          const monthMap = {
            jan: 'มกราคม',
            feb: 'กุมภาพันธ์',
            mar: 'มีนาคม',
            apr: 'เมษายน',
            may: 'พฤษภาคม',
            jun: 'มิถุนายน',
            jul: 'กรกฎาคม',
            aug: 'สิงหาคม',
            sep: 'กันยายน',
            oct: 'ตุลาคม',
            nov: 'พฤศจิกายน',
            dec: 'ธันวาคม',
          };
          return monthMap[month.toLowerCase()] || month; // คืนค่าภาษาไทย หรือคืนค่าเดิมถ้าไม่มีใน map
        }

        // ข้อมูลดั้งเดิมในภาษาอังกฤษ
        const rawData = [
          ['jan', 1, 3, 2, 0, 2],
          ['feb', 0, 1, 2, 2, 0],
          ['mar', 2, 1, 3, 3, 1],
          ['apr', 4, 2, 6, 7, 5],
          ['may', 8, 3, 0, 2, 7],
          ['jun', 10, 4, 5, 1, 6],
          ['jul', 2, 5, 8, 0, 1],
          ['aug', 2, 1, 1, 0, 2],
          ['sep', 1, 7, 2, 3, 5],
          ['oct', 3, 6, 3, 4, 2],
          ['nov', 0, 0, 0, 0, 0],
          ['dec', 0, 0, 0, 0, 0],
        ];

        // แปลงชื่อเดือนเป็นภาษาไทย
        const formattedData = rawData.map(row => [convertMonthToThai(row[0]), ...row.slice(1)]);

        // สร้าง DataTable
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'เดือน');
        data.addColumn('number', 'เว็บไซต์บริษัทฯ');
        data.addColumn({ type: 'string', role: 'annotation' }); // Annotation for เว็บไซต์บริษัทฯ
        data.addColumn('number', 'Facebook บริษัทฯ');
        data.addColumn({ type: 'string', role: 'annotation' }); // Annotation for Facebook บริษัทฯ
        data.addColumn('number', 'กรมจัดหางาน');
        data.addColumn({ type: 'string', role: 'annotation' }); // Annotation for กรมจัดหางาน
        data.addColumn('number', 'ญาติพี่น้อง/เพื่อน');
        data.addColumn({ type: 'string', role: 'annotation' }); // Annotation for ญาติพี่น้อง/เพื่อน
        data.addColumn('number', 'เว็บไซต์ท้องถิ่น');
        data.addColumn({ type: 'string', role: 'annotation' }); // Annotation for เว็บไซต์ท้องถิ่น

        // เพิ่มข้อมูลที่แปลงแล้ว
        formattedData.forEach(row => {
          data.addRow([
            row[0],
            row[1], row[1] > 1 ? row[1].toString() : null,
            row[2], row[2] > 1 ? row[2].toString() : null,
            row[3], row[3] > 1 ? row[3].toString() : null,
            row[4], row[4] > 1 ? row[4].toString() : null,
            row[5], row[5] > 1 ? row[5].toString() : null,
          ]);
        });

        var options = {
          title: 'ช่องทางการรับสมัครงานในแต่ละเดือน',
          isStacked: true,
          hAxis: { title: 'เดือน' },
          vAxis: { title: 'จำนวนผู้สมัคร' },
          legend: { position: 'top', maxLines: 3 },
          colors: ['#3366cc', '#dc3912', '#ff9900', '#109618', '#990099'],
          annotations: {
            alwaysOutside: false,
            textStyle: {
              fontSize: 10,
              color: '#ffffff',
              auraColor: 'none',
            },
          },
        };

        var chart = new google.visualization.ColumnChart(
          document.getElementById('chart_div')
        );

        chart.draw(data, options);
      }
       
    </script>
  </head>
  <body>
    <div id="chart_div" style="width: 100%; height: 500px;"></div>
  </body>
</html>
