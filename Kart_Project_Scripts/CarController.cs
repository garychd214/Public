using System.Collections;
using System.Collections.Generic;
using UnityEngine;


public class CarController : MonoBehaviour
{
    public WheelCollider WheelFL;
    public WheelCollider WheelFR;
    public WheelCollider WheelRL;
    public WheelCollider WheelRR;
    public Transform WheelFLtrans;
    public Transform WheelFRtrans;
    public Transform WheelRLtrans;
    public Transform WheelRRtrans;
    public Vector3 eulertest;
    //float maxFwdSpeed = -3000;
    //float maxBwdSpeed = 1000f;
    float gravity = 9.8f;
    private bool braked = false;
    private float maxBrakeTorque = 500;
    private Rigidbody rb;

    public float AntiRoll = 5000.0f;

    //public Transform centreofmass;
    private float maxTorque = 1000;

    public GameObject Player;

    public Camera firstCam, thirdCam;

    void Start()
    {
        rb = GetComponent<Rigidbody>();
        cam1();
        Player.transform.position = new Vector3(465.1676f, 0.1298f, 198f);
        Player.transform.eulerAngles = new Vector3(0, 270, 0);
    }

    void FixedUpdate()
    {
        if (!braked)
        {
            WheelFL.brakeTorque = 0;
            WheelFR.brakeTorque = 0;
            WheelRL.brakeTorque = 0;
            WheelRR.brakeTorque = 0;
        }
        //speed of car, Car will move as you will provide the input to it.

        WheelRR.motorTorque = maxTorque * Input.GetAxis("Vertical");
        WheelRL.motorTorque = maxTorque * Input.GetAxis("Vertical");

        //changing car direction
        //Here we are changing the steer angle of the front tyres of the car so that we can change the car direction.
        WheelFL.steerAngle = 40 * (Input.GetAxis("Horizontal"));
        WheelFR.steerAngle = 40 * Input.GetAxis("Horizontal");


    }
    void Update()
    {
        HandBrake();

        //for tyre rotate
        WheelFLtrans.Rotate(WheelFL.rpm / 90 * 360 * Time.deltaTime, 0, 0);
        WheelFRtrans.Rotate(WheelFR.rpm / 90 * 360 * Time.deltaTime, 0, 0);
        WheelRLtrans.Rotate(WheelRL.rpm / 90 * 360 * Time.deltaTime, 0, 0);
        WheelRRtrans.Rotate(WheelRL.rpm / 90 * 360 * Time.deltaTime, 0, 0);
        //changing tyre direction
        Vector3 temp = WheelFLtrans.localEulerAngles;
        Vector3 temp1 = WheelFRtrans.localEulerAngles;
        temp.y = WheelFL.steerAngle - (WheelFLtrans.localEulerAngles.z);
        WheelFLtrans.localEulerAngles = temp;
        temp1.y = WheelFR.steerAngle - WheelFRtrans.localEulerAngles.z;
        WheelFRtrans.localEulerAngles = temp1;
        eulertest = WheelFLtrans.localEulerAngles;

        
        //Anti- Rollover
        WheelHit hit;
        float travelL = 1.0f;
        float travelR = 1.0f;
        bool groundedL = WheelFL.GetGroundHit(out hit);
        if (groundedL)
        {
            travelL = (-WheelFL.transform.InverseTransformPoint(hit.point).y - WheelFL.radius) / WheelFL.suspensionDistance;
        }
        bool groundedR = WheelFR.GetGroundHit(out hit);
        if (groundedR)
        {
            travelR = (-WheelFR.transform.InverseTransformPoint(hit.point).y - WheelFR.radius) / WheelFR.suspensionDistance;
        }

        float antiRollForce = (travelL - travelR) * AntiRoll;



        if (groundedL)

            GetComponent<Rigidbody>().AddForceAtPosition(WheelFL.transform.up * -antiRollForce, WheelFL.transform.position);

        if (groundedR)

            GetComponent<Rigidbody>().AddForceAtPosition(WheelFR.transform.up * antiRollForce, WheelFR.transform.position);
       
    }
        
    void LateUpdate()
    {
        if (Input.GetKeyDown(KeyCode.C))
        {
            //Debug.Log("1");
            if (firstCam.enabled == true)
            {
                cam3();
                //Debug.Log("2");
            }
            else
            {
                cam1();
                //Debug.Log("3");
            }
        }
        if (Input.GetKeyDown(KeyCode.R))
        {
            float angleY = Player.transform.rotation.eulerAngles.y;
            Player.transform.eulerAngles = new Vector3(0, angleY, 0);
        }
    }
    void HandBrake()
    {
        //Debug.Log("brakes " + braked);
        if (Input.GetButton("Jump"))
        {
            braked = true;
        }
        else
        {
            braked = false;
        }
        if (braked)
        {

            WheelRL.brakeTorque = maxBrakeTorque * 20;//0000;
            WheelRR.brakeTorque = maxBrakeTorque * 20;//0000;
            WheelRL.motorTorque = 0;
            WheelRR.motorTorque = 0;
        }
    }
    public void cam1()
    {
        thirdCam.enabled = false;
        firstCam.enabled = true;
    }
    public void cam3()
    {
        firstCam.enabled = false;
        thirdCam.enabled = true;
    }
    void reset()
    {
        //Debug.Log("2");
        Player.transform.Translate(0f, 8f, 0f, Space.World);
        Player.transform.rotation *= Change(0, 1, 0);
    }
    private static Quaternion Change(float x, float y, float z)
    {
        //Return the new Quaternion
        return new Quaternion(x, y, z, 1);
    }


}