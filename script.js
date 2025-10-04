// ---------- Sidebar Toggle ----------
const sidebar = document.getElementById('sidebar');
const hamburger = document.querySelector('.hamburger');
const overlay = document.getElementById('overlay');
const pages = document.querySelectorAll('.page');

hamburger.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
});

overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
});

// ---------- Show Page Function ----------
function showPage(id) {
    pages.forEach(page => page.style.display = 'none');
    document.getElementById(id).style.display = 'block';
    sidebar.classList.remove('active');
    overlay.classList.remove('active');
}

// Show home page by default
showPage('home');

// ---------- Fetch Donor Data for Dashboard ----------
window.addEventListener('DOMContentLoaded', () => {
    if(document.getElementById('dashboardCards') || document.getElementById('dataList')) {
        fetchDonorData();
    }
});

function fetchDonorData() {
    fetch('view_data.php')
        .then(res => res.json())
        .then(data => {
            renderDashboard(data);
            renderDonors(data);
        })
        .catch(err => console.error('Error fetching data:', err));
}

// ---------- Render Dashboard Cards ----------
function renderDashboard(data) {
    const totalDonors = data.length;
    const recentDonor = totalDonors > 0 ? data[totalDonors - 1].name : 'N/A';

    const bloodGroups = {};
    data.forEach(d => {
        bloodGroups[d.blood] = (bloodGroups[d.blood] || 0) + 1;
    });

    let bloodSummary = '';
    for (let bg in bloodGroups) {
        bloodSummary += `${bg}: ${bloodGroups[bg]}<br>`;
    }

    // update static HTML placeholders
    document.getElementById('totalDonorsCount').innerText = totalDonors;
    document.getElementById('recentDonorName').innerText = recentDonor;
    document.getElementById('bloodGroupCount').innerText = Object.keys(bloodGroups).length;
    document.getElementById('bloodSummaryList').innerHTML = bloodSummary;

}

// fetch donor data from PHP and render
fetch("get_donors.php")
    .then(res => res.json())
    .then(data => {
        console.log("Donor data:", data); // check in browser console
        renderDashboard(data);
    })
    .catch(err => console.error(err));


// ---------- Render Donor List ----------
function renderDonors(data) {
    const dataList = document.getElementById('dataList');
    if(!dataList) return;

    dataList.innerHTML = '';
    data.forEach(d => {
        const box = document.createElement('div');
        box.classList.add('data-box');
        box.innerHTML = `
            <strong>Name:</strong> ${d.name} || 
            <strong>Age:</strong> ${d.age} || 
            <strong>Gender:</strong> ${d.gender} || 
            <strong>Blood:</strong> ${d.blood} || 
            <strong>Place:</strong> ${d.place} || 
            <strong>Contact:</strong> ${d.contact} 
            ${d.canEdit ? `<button onclick="editDonor(${d.id})" class="btn">Edit</button>` : ''}
        `;
        dataList.appendChild(box);
    });
}

// ---------- Edit Donor ----------
function editDonor(id){
    window.location.href = `edit.php?id=${id}`;
}

// ---------- OTP / Forgot Password ----------
const sendOtpBtn = document.getElementById('sendOtpBtn');
if(sendOtpBtn){
    sendOtpBtn.addEventListener('click', () => {
        const mobile = document.getElementById('mobile').value.trim();
        if(mobile === '') { alert('Enter your mobile number'); return; }

        fetch('send_otp.php', {
            method: 'POST',
            headers: { 'Content-Type':'application/x-www-form-urlencoded' },
            body: `mobile=${mobile}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                alert('OTP sent successfully!');
                window.location.href = 'verify_otp.php?mobile=' + mobile;
            } else {
                alert(data.message || 'Error sending OTP');
            }
        });
    });
}

// ---------- Verify OTP ----------
const verifyOtpBtn = document.getElementById('verifyOtpBtn');
if(verifyOtpBtn){
    verifyOtpBtn.addEventListener('click', () => {
        const otp = document.getElementById('otp').value.trim();
        const mobile = document.getElementById('mobile').value.trim();
        if(otp === '') { alert('Enter OTP'); return; }

        fetch('verify_otp.php', {
            method: 'POST',
            headers: { 'Content-Type':'application/x-www-form-urlencoded' },
            body: `mobile=${mobile}&otp=${otp}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                alert('OTP verified! You can reset password.');
                window.location.href = 'reset_password.php?mobile=' + mobile;
            } else {
                alert(data.message || 'Invalid OTP');
            }
        });
    });
}

// ---------- Reset Password ----------
const resetPasswordBtn = document.getElementById('resetPasswordBtn');
if(resetPasswordBtn){
    resetPasswordBtn.addEventListener('click', () => {
        const password = document.getElementById('password').value.trim();
        const confirm = document.getElementById('confirm_password').value.trim();
        const mobile = document.getElementById('mobile').value.trim();

        if(password === '' || confirm === '') { alert('Fill all fields'); return; }
        if(password !== confirm) { alert('Passwords do not match'); return; }

        fetch('reset_password.php', {
            method: 'POST',
            headers: { 'Content-Type':'application/x-www-form-urlencoded' },
            body: `mobile=${mobile}&password=${password}`
        })
        .then(res => res.json())
        .then(data => {
            if(data.success){
                alert('Password reset successful! Login now.');
                window.location.href = 'login.php';
            } else {
                alert(data.message || 'Error resetting password');
            }
        });
    });
}

