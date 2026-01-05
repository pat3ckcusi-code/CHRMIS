document.addEventListener('DOMContentLoaded', () => {
  const ctx = document.getElementById('employeesPerDeptChart');
  if (!ctx) return; // only run when chart exists

  fetch('../api/get_employees_per_department.php')
    .then(res => res.json())
    .then(result => {
      if (result.status !== 'success') throw new Error(result.message);

      const labels = result.data.map(row => row.department);
      const values = result.data.map(row => row.total);

      new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [{
            label: 'Employees',
            data: values,
            borderWidth: 1,
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: { display: false },
            tooltip: { enabled: true },
          },
          scales: {
            y: { beginAtZero: true }
          }
        }
      });
    })
    .catch(err => {
      console.error(err);
      ctx.parentNode.innerHTML = "<p class='text-danger text-center'>Failed to load chart.</p>";
    });
});
